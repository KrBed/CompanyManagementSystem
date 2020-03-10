<?php

namespace App\Repository;

use App\Entity\DutyRooster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DutyRooster|null find($id, $lockMode = null, $lockVersion = null)
 * @method DutyRooster|null findOneBy(array $criteria, array $orderBy = null)
 * @method DutyRooster[]    findAll()
 * @method DutyRooster[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DutyRoosterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DutyRooster::class);
    }

    // /**
    //  * @return DutyRooster[] Returns an array of DutyRooster objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DutyRooster
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
