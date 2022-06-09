<?php

namespace App\Repository;

use App\Entity\UserMonthStatistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserMonthStatistics|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMonthStatistics|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMonthStatistics[]    findAll()
 * @method UserMonthStatistics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMonthStatisticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMonthStatistics::class);
    }

    // /**
    //  * @return UserMonthStatistics[] Returns an array of UserMonthStatistics objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserMonthStatistics
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
