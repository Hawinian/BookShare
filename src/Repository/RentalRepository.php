<?php
/**
 * Rental repository.
 */

namespace App\Repository;

use App\Entity\Rental;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class RentalRepository.
 *
 * @method Rental|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rental|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rental[]    findAll()
 * @method Rental[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentalRepository extends ServiceEntityRepository
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
     * RentalRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rental::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAllLateBooks(): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
//            ->select(
//                'rental.id, rental.date_of_return, rental.date_of_rental',
//                'book.id, book.title'
//            )
            ->join('rental.book', 'book')
            ->join('rental.user', 'user')
            ->where('rental.date_of_return < CURRENT_DATE()')
            ->orderBy('rental.date_of_rental', 'DESC');

        return $queryBuilder;

    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->join('rental.book', 'book')
            ->join('rental.user', 'user')
            ->orderBy('rental.date_of_rental', 'DESC');
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAllInTimeBooks(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->join('rental.book', 'book')
            ->join('rental.user', 'user')
            ->where('rental.date_of_return >= CURRENT_DATE()')
            ->orderBy('rental.date_of_rental', 'DESC');
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
        return $queryBuilder ?? $this->createQueryBuilder('rental');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Rental $rental Rental entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Rental $rental): void
    {
        $this->_em->persist($rental);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Rental $rental Rental entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Rental $rental): void
    {
        $this->_em->remove($rental);
        $this->_em->flush();
    }

    /**
     * Query tasks by author.
     *
     * @param \App\Entity\User $user User entity
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryByAuthor(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('rental.user = :user')
            ->setParameter('user', $user);

        return $queryBuilder;
    }
}
