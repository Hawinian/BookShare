<?php
/**
 * PetitionKind repository.
 */

namespace App\Repository;

use App\Entity\PetitionKind;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class PetitionKindRepository.
 *
 * @method PetitionKind|null find($id, $lockMode = null, $lockVersion = null)
 * @method PetitionKind|null findOneBy(array $criteria, array $orderBy = null)
 * @method PetitionKind[]    findAll()
 * @method PetitionKind[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PetitionKindRepository extends ServiceEntityRepository
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
     * PetitionKindRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PetitionKind::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('petition_kind.name', 'ASC');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\PetitionKind $petition_kind PetitionKind entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(PetitionKind $petition_kind): void
    {
        $this->_em->persist($petition_kind);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\PetitionKind $petition_kind PetitionKind entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(PetitionKind $petition_kind): void
    {
        $this->_em->remove($petition_kind);
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
        return $queryBuilder ?? $this->createQueryBuilder('petition_kind');
    }
}
