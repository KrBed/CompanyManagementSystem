<?php

namespace App\Repository;

use App\Entity\PayRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PayRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayRate[]    findAll()
 * @method PayRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayRate::class);
    }

    // /**
    //  * @return PayRate[] Returns an array of PayRate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PayRate
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
