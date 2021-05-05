<?php

namespace App\Repository;

use App\Entity\AccountStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccountStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountStatus[]    findAll()
 * @method AccountStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountStatus::class);
    }

    // /**
    //  * @return AccountStatus[] Returns an array of AccountStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccountStatus
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
