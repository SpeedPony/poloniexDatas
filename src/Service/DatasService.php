<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 03/02/2018
 * Time: 01:31
 */

namespace App\Service;

use App\Constant\PairConstant;
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
     * @var MailService
     */
    private $mailService;

    /**
     * DatasService constructor.
     * @param PairRepository $pairRepository
     * @param DatasRepository $datasRepository
     * @param MailService $mailService
     */
    public function __construct(PairRepository $pairRepository, DatasRepository $datasRepository, MailService $mailService) {
        $this->pairRepository = $pairRepository;
        $this->datasRepository = $datasRepository;
        $this->mailService = $mailService;
    }

    /**
     * @param $json
     * @return array
     */
    private function decodeJson($json) {
        return json_decode($json, true);
    }

    /**
     * @param $json
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveDatas($json) {
        $this->datasRepository->deleteOldDatas();
        $this->mailService->resetMailSent();

        $datas = $this->decodeJson($json);
        foreach ($datas as $pair => $value) {
            if (strpos($pair, 'BTC') !== false && strpos($pair, 'USDT') === false) {
                $pairEntity = $this->pairRepository->findOneBy(array('name' => $pair));
                if(in_array($pair, PairConstant::MARGIN)) {
                    if (is_null($pairEntity)) {
                        $pairEntity = new Pair();
                        $pairEntity->setName($pair);
                        $pairEntity->setDateMail(null);
                        $pairEntity->setMailSent(false);
                        $this->pairRepository->create($pairEntity);
                    }

                    $datasEntity = new Datas();
                    $datasEntity->setDate(new \DateTime());
                    $datasEntity->setPair($pairEntity);
                    $datasEntity->setValue($value['BTC']);
                    $this->datasRepository->create($datasEntity);

                    $this->analyseDatasForMail($pairEntity, $value['BTC']);
                }
            }
        }
    }

    /**
     * @param Pair $pair
     * @param $value
     * @throws \Exception
     */
    public function analyseDatasForMail(Pair $pair, $value) {
        $datas = $this->datasRepository->getDatas($pair, $value);
        if(count($datas) > 0) {
            $this->mailService->sendMail($pair);
        }
    }
}