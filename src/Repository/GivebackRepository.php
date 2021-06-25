<?php
/**
 * Giveback repository.
 */

namespace App\Repository;

use App\Entity\Giveback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class GivebackRepository.
 *
 * @method Giveback|null find($id, $lockMode = null, $lockVersion = null)
 * @method Giveback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Giveback[]    findAll()
 * @method Giveback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GivebackRepository extends ServiceEntityRepository
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
    const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * GivebackRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Giveback::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->join('giveback.rental', 'rental')
            ->join('rental.user', 'user')
            ->join('rental.book', 'book')
            ->orderBy('giveback.date', 'DESC');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Giveback $giveback Giveback entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Giveback $giveback): void
    {
        $this->_em->persist($giveback);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Giveback $giveback Giveback entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Giveback $giveback): void
    {
        $this->_em->remove($giveback);
        $this->_em->flush();
    }

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('giveback');
    }
}
