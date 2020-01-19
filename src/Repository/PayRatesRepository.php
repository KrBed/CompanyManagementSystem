<?php

namespace App\Repository;

use App\Entity\PayRates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PayRates|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayRates|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayRates[]    findAll()
 * @method PayRates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayRatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayRates::class);
    }

    // /**
    //  * @return PayRates[] Returns an array of PayRates objects
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
    public function findOneBySomeField($value): ?PayRates
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
