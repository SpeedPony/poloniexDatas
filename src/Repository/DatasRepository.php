<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 03/02/2018
 * Time: 02:34
 */

namespace App\Repository;

use App\Entity\Datas;
use App\Entity\Pair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class DatasRepository
 * @package App\Repository
 */
class DatasRepository extends ServiceEntityRepository
{
    /**
     * PairRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Datas::class);
    }

    /**
     * @param Datas $datas
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Datas $datas) {
        $this->getEntityManager()->persist($datas);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Pair $pair
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function getDatas(Pair $pair, $value) {
        $value = $value * 0.66;
        $now = new \DateTime();
        $yesterday = (clone $now)->sub(new \DateInterval('P1D'));
        $subqb = $this->createQueryBuilder('d1');
        $subqb->select("avg(d1.value)")
            ->leftJoin('d1.pair', 'p1')
            ->andWhere($subqb->expr()->eq('p1.id', ':pairId'))
            ->andWhere($subqb->expr()->between('d1.date', ':yesterday', ':now'));

        $qb = $this->createQueryBuilder('d');
        $qb->leftJoin('d.pair', 'p')
            ->andWhere($subqb->expr()->eq('p.id', ':pairId'))
            ->andWhere($qb->expr()->gte( $value, '(' . $subqb->getDQL() . ')'))
            ->setParameter('pairId', $pair->getId())
            ->setParameter('now', $now)
            ->setParameter('yesterday', $yesterday);

        return $qb->getQuery()->getResult();
    }

    /**
     *
     */
    public function deleteOldDatas() {
        $now = new \DateTime();
        $now->sub( new \DateInterval('P5D') );
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->delete('App\Entity\Datas', 'd')
            ->andWhere($qb->expr()->lte('d.date', ':date'))
            ->setParameter('date', $now);
        $query->getQuery()->execute();
    }
}