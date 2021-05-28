<?php
/**
 * Rental service.
 */

namespace App\Service;

use App\Entity\Rental;
use App\Entity\User;
use App\Repository\RentalRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RentalService.
 */
class RentalService
{
    /**
     * Rental repository.
     *
     * @var \App\Repository\RentalRepository
     */
    private $rentalRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * RentalService constructor.
     *
     * @param \App\Repository\RentalRepository     $rentalRepository Rental repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator           Paginator
     */
    public function __construct(RentalRepository $rentalRepository, PaginatorInterface $paginator)
    {
        $this->rentalRepository = $rentalRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedListAuthor(int $page, User $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->rentalRepository->queryByAuthor($user),
            $page,
            RentalRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find rental by Id.
     *
     * @param int $id Rental Id
     *
     * @return \App\Entity\Rental|null Rental entity
     */
    public function findOneById(int $id): ?Rental
    {
        return $this->rentalRepository->findOneById($id);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Rental $rental): void
    {
        $this->rentalRepository->save($rental);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Rental $rental): void
    {
        $this->rentalRepository->delete($rental);
    }
}
