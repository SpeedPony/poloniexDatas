<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 03/02/2018
 * Time: 02:34
 */

namespace App\Repository;

use App\Entity\Alert;
use App\Entity\Pair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AlertRepository
 * @package App\Repository
 */
class AlertRepository extends ServiceEntityRepository
{
    /**
     * PairRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Alert::class);
    }

    /**
     * @param Alert $alert
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Alert $alert) {
        $this->getEntityManager()->persist($alert);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Pair $pair
     */
    public function saveAlert(Pair $pair) {
        $alert = new Alert();
        $alert->setPair($pair);
        $alert->setDate(new \DateTime());
        $this->create($alert);
    }

    /**
     * @param \DateTime $minDate
     * @param \DateTime $maxDate
     * @return array
     */
    public function getDatas($minDate, $maxDate) {
        $qb = $this->createQueryBuilder('a');
        $query = $qb->select('count(a.id) as count')
            ->addSelect('p.name as name')
            ->leftJoin('a.pair', 'p')
            ->andWhere($qb->expr()->between('a.date', ':minDate', ':maxDate'))
            ->setParameter('minDate', $minDate)
            ->setParameter('maxDate', $maxDate);

        return $qb->getQuery()->getResult();
    }
}