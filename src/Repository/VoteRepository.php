<?php
/**
 * Vote repository.
 */

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class VoteRepository.
 *
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * VoteRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

//    /**
//     * Query all records.
//     *
//     * @return \Doctrine\ORM\QueryBuilder Query builder
//     */
//    public function queryAll(): QueryBuilder
//    {
//        $queryBuilder = $this->getOrCreateQueryBuilder()
//            ->select(
//                'b.id',
//                'avg(vote.rate) avg_rate'
//            )
//            ->leftJoin('vote.book', 'b')
//            ->orderBy('b.id', 'ASC')
//            ->groupBy('b.id');
//
//        return $queryBuilder;
//    }

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('vote');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Vote $vote Vote entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Vote $vote): void
    {
        $this->_em->persist($vote);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Vote $vote Vote entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Vote $vote): void
    {
        $this->_em->remove($vote);
        $this->_em->flush();
    }

//    /**
//     * @param $book_id
//     *
//     * @return int|mixed|string
//     */
//    public function countAverage($book_id)
//    {
//        return $this->createQueryBuilder('v')
//            ->select('avg(v.rate)')
//            ->andWhere('v.book.id = :bok')
//            ->setParameter('bok', $book_id)
//            ->getQuery()
//            ->getResult();
//    }
}
