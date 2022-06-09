<?php

namespace App\Repository;

use App\Entity\WorkStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method WorkStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkStatus[]    findAll()
 * @method WorkStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkStatus::class);
    }

    /**
     * @param $user
     * @param $dateFrom
     * @param $dateTo
     * @return int|mixed|string
     */
    public function findWorkStatusByDate($user, $dateFrom, $dateTo){
        return $this->createQueryBuilder('w')
            ->andWhere('w.user = :val')
            ->andWhere('w.date >= :dateFrom')
            ->setParameter('dateFrom',$dateFrom)
            ->andWhere('w.date <= :dateTo')
            ->setParameter('dateTo',$dateTo)
            ->setParameter('val', $user->getId())
            ->orderBy('w.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return WorkStatus[] Returns an array of WorkStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkStatus
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
