<?php

namespace App\Repository;

use App\Entity\PetitionKind;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PetitionKind|null find($id, $lockMode = null, $lockVersion = null)
 * @method PetitionKind|null findOneBy(array $criteria, array $orderBy = null)
 * @method PetitionKind[]    findAll()
 * @method PetitionKind[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetitionKindRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PetitionKind::class);
    }

    // /**
    //  * @return PetitionKind[] Returns an array of PetitionKind objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PetitionKind
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
