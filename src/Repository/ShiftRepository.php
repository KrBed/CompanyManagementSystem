<?php

namespace App\Repository;

use App\Entity\Shift;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Shift|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shift|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shift[]    findAll()
 * @method Shift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiftRepository extends ServiceEntityRepository
{
    /**
     * @var ManagerRegistry
     */
    private $registry;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Shift::class);
        $this->registry = $registry;
        $this->em = $em;
    }

    public function removeUserShiftsByDate($userId, \DateTime $startDate, \DateTime $endDate)
    {
        $qb = $this->createQueryBuilder('s')->leftJoin('s.user', 'user')->addSelect('user');
        $shifts = $qb->andWhere('user.id LIKE :userId')->setParameter('userId', $userId)->andWhere('s.date >= :startDate AND s.date <= :endDate')
            ->setParameter('startDate', $startDate)->setParameter('endDate', $endDate)->getQuery()->execute();

        foreach ($shifts as $shift) {
            $this->em->remove($shift);
        }
        $this->em->flush();

        return $shifts;
    }

    public function findUserShiftsByDate($user, \DateTime $startDate, \DateTime $endDate)
    {
        /**
         * @var User $user
         */
        $userId = $user->getId();
        $qb = $this->createQueryBuilder('s')->leftJoin('s.user','u')->addSelect('u');
        $shifts = $qb->andWhere('u.id LIKE :userId')->setParameter('userId', $userId)->andWhere('s.date >= :startDate AND s.date <= :endDate')
            ->setParameter('startDate', $startDate)->setParameter('endDate', $endDate)->getQuery()->execute();
        return $shifts;
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
