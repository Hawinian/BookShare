<?php
/**
 * Petition repository.
 */

namespace App\Repository;

use App\Entity\Petition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class PetitionRepository.
 *
 * @method Petition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Petition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Petition[]    findAll()
 * @method Petition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetitionRepository extends ServiceEntityRepository
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
     * PetitionRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Petition::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->join('petition.book', 'book')
            ->join('petition.user', 'user')
            ->orderBy('petition.date', 'DESC');
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
        return $queryBuilder ?? $this->createQueryBuilder('petition');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Petition $petition Petition entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Petition $petition): void
    {
        $this->_em->persist($petition);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Petition $petition Petition entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Petition $petition): void
    {
        $this->_em->remove($petition);
        $this->_em->flush();
    }
}
