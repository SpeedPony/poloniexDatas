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
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * DatasService constructor.
     * @param PairRepository $pairRepository
     * @param DatasRepository $datasRepository
     * @param \Swift_Mailer $mailer
     */
    public function __construct(PairRepository $pairRepository, DatasRepository $datasRepository, \Swift_Mailer $mailer) {
        $this->pairRepository = $pairRepository;
        $this->datasRepository = $datasRepository;
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function sendMail(Pair $pair) {

        /** @var Pair $pair */
        if (!$pair->isMailSent()) {
            $email = (new \Swift_Message('Poloniex'))
                ->setFrom('qdebay.smtp@gmail.com')
                ->setTo('qdebay@gmail.com')
                ->setBody(sprintf("Alerte volume sur la monnaie %s.", $pair->getName()));
            $this->mailer->send($email);
            $this->pairRepository->updateMailSend($pair);
        }
    }
}