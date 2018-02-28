<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 27/02/2018
 * Time: 21:24
 */

namespace App\Service;


use App\Constant\TimeConstant;
use App\Entity\Pair;
use App\Repository\AlertRepository;
use App\Repository\DatasRepository;
use App\Repository\PairRepository;

class MailService {

    /**
     * @var PairRepository
     */
    private $pairRepository;

    /**
     * @var DatasRepository
     */
    private $datasRepository;

    /**
     * @var AlertRepository
     */
    private $alertRepository;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * DatasService constructor.
     * @param PairRepository $pairRepository
     * @param DatasRepository $datasRepository
     * @param AlertRepository $alertRepository
     * @param \Swift_Mailer $mailer
     */
    public function __construct(PairRepository $pairRepository, DatasRepository $datasRepository, AlertRepository $alertRepository, \Swift_Mailer $mailer) {
        $this->pairRepository = $pairRepository;
        $this->datasRepository = $datasRepository;
        $this->alertRepository = $alertRepository;
        $this->mailer = $mailer;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function resetMailSent() {
        $pairs = $this->pairRepository->getPairEmailSentOverdue();
        foreach ($pairs as $pair) {
            $pair->setMailSent(false);
            $pair->setDateMail(null);
            $this->pairRepository->create($pair);
        }
    }

    /**
     * @param array $data
     * @param boolean $alert
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function sendMail($data, $alert) {

        /** @var Pair $pair */
        $pair = $this->pairRepository->findOneBy(array('name' => $data['pair']));
        if (!$pair->isMailSent()) {
            $email = (new \Swift_Message('Poloniex'))
                ->setFrom('qdebay.smtp@gmail.com')
                ->setTo('qdebay@gmail.com')
                ->setBody(sprintf("La monnaie %s a fait %s %% (%s).", $data['pair'], $data['pourc'], $data['value']));
            $this->mailer->send($email);
            $this->pairRepository->updateMailSend($pair);
            $this->alertRepository->saveAlert($pair);
        }
    }
}