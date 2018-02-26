<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 03/02/2018
 * Time: 02:34
 */

namespace App\Repository;

use App\Entity\Datas;
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
     *
     */
    public function updatePosition() {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->update('App\Entity\Datas', 'd')
            ->set('d.position', 'd.position + 1')
            ->getQuery();
        $query->execute();
    }

    /**
     * @param array $position
     * @return array
     */
    public function getDatas($position) {
        $qb = $this->createQueryBuilder('d');
        $query = $qb->select('d.value as value')
            ->addSelect('d.position as position')
            ->addSelect('p.name as name')
            ->leftJoin('d.pair', 'p')
            ->andWhere($qb->expr()->in('d.position', ':position'))
            ->setParameter('position', $position);

        return $query->getQuery()->getResult();
    }

    /**
     *
     */
    public function deleteOldDatas() {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->delete('App\Entity\Datas', 'd')
            ->andWhere($qb->expr()->gte('d.position', 300));
        $query->getQuery()->execute();
    }
}