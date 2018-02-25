<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 03/02/2018
 * Time: 02:34
 */

namespace App\Repository;

use App\Entity\Pair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class PairRepository
 * @package App\Repository
 */
class PairRepository extends ServiceEntityRepository
{
    /**
     * PairRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pair::class);
    }

    /**
     * @param Pair $pair
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Pair $pair) {
        $this->getEntityManager()->persist($pair);
        $this->getEntityManager()->flush();
    }
}