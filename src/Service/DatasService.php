<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 03/02/2018
 * Time: 01:31
 */

namespace App\Service;

use App\Entity\Datas;
use App\Entity\Pair;
use App\Repository\DatasRepository;
use App\Repository\PairRepository;
use App\VO\MainVO;
use App\VO\TimeVO;
use Symfony\Component\BrowserKit\Client;

/**
 * Class APIService
 * @package App\Service
 */
class DatasService {

    /**
     * @var PairRepository
     */
    private $pairRepository;

    /**
     * @var DatasRepository
     */
    private $datasRepository;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * DatasService constructor.
     * @param PairRepository $pairRepository
     * @param DatasRepository $datasRepository
     */
    public function __construct(PairRepository $pairRepository, DatasRepository $datasRepository, \Swift_Mailer $mailer) {
        $this->pairRepository = $pairRepository;
        $this->datasRepository = $datasRepository;
        $this->mailer = $mailer;
    }

    /**
     * @param $json
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveDatas($json) {
        $datas = $this->decodeJson($json);
        foreach ($datas as $pair => $value) {
            if (strpos($pair, 'BTC') !== false && strpos($pair, 'USDT') == false) {
                $pairEntity = $this->pairRepository->findOneBy(array('name' => $pair));
                if (is_null($pairEntity)) {
                    $pairEntity = new Pair();
                    $pairEntity->setName($pair);
                    $this->pairRepository->create($pairEntity);
                }
                $datasEntity = new Datas();
                $datasEntity->setDate(new \DateTime());
                $datasEntity->setPair($pairEntity);
                $datasEntity->setValue($value['last']);
                $datasEntity->setPosition(0);
                $this->datasRepository->create($datasEntity);
            }
        }
        $this->datasRepository->updatePosition();
        $this->deleteOldDatas();
        $this->sendMail();
    }

    /**
     *
     */
    public function deleteOldDatas() {
        $this->datasRepository->deleteOldDatas();
    }

    /**
     * @param $json
     * @return array
     */
    private function decodeJson($json) {
        return json_decode($json, true);
    }

    /**
     * @param $numbers
     * @return array
     */
    public function getDatas($numbers) {
        $datas = $this->datasRepository->getDatas(array_merge($numbers, array(1)));
        $formatedDatas = array();
        foreach($datas as $data) {
            $formatedDatas[$data['name']][$data['position']] = $data['value'];
        }

        $retour = array();
        $i = 1;
        foreach($formatedDatas as $key => $data) {
            $vo = new MainVO();
            $vo->setPair($key);
            foreach($numbers as $time) {
                $timeVO = new TimeVO();
                $timeVO->setPouc(number_format(((($data[1] - $data[$time]) / $data[$time]) * 100) , 3));
                $timeVO->setBrut($data[$time]);
                $vo->addData($timeVO, $time);
            }
            $timeVO = new TimeVO();
            $timeVO->setBrut($data[1]);
            $vo->addData($timeVO, 1);
            $retour[$i++] = $vo;
        }

        return $retour;
    }

    /**
     * @param MainVO[] $datas
     * @return MainVO[]
     */
    public function formatDatas($datas) {
        foreach($datas as $main) {
            foreach($main->getDatas() as $time) {
                if(!is_null($time->getPouc()) && $time->getPouc() > 0) {
                    $time->setPouc('+'.$time->getPouc().'%');
                    $time->setClass('green');
                }
                else {
                    $time->setPouc($time->getPouc().'%');
                    $time->setClass('red');
                }
            }
        }

        return $datas;
    }

    /**
     *
     */
    public function sendMail() {

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('qdebay.smtp@gmail.com')
            ->setTo('qdebay@gmail.com')
            ->setBody('test');

        $this->mailer->send($message);

        $datas = $this->datasRepository->getDatas(array(1,10));
        $formatedDatas = array();
        foreach($datas as $data) {
            $formatedDatas[$data['name']][$data['position']] = $data['value'];
        }

        $retour = array();
        $i = 0;
        foreach($formatedDatas as $key => $data) {
            $pourcentage = number_format(((($data[1] - $data[10]) / $data[10]) * 100) , 3);
            if($pourcentage > 10 || $pourcentage < -10) {
                $retour[$i]['pair'] = $key;
                $retour[$i]['pourc'] = $pourcentage;
            }
        }

        if(count($retour) > 0) {
            $message = "";
            foreach($retour as $data) {
                $message .= sprintf("La monnaie %s a fait %s %. <br/>", $data['pair'], $data['pourc']);
            }

            $message = (new \Swift_Message('Poloniex'))
                ->setFrom('qdebay.smtp@gmail.com')
                ->setTo('qdebay@gmail.com')
                ->setBody($message);

            $this->mailer->send($message);
        }
    }
}