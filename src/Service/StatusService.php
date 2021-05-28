<?php
/**
 * Status service.
 */

namespace App\Service;

use App\Entity\Status;
use App\Repository\StatusRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class StatusService.
 */
class StatusService
{
    /**
     * Status repository.
     *
     * @var \App\Repository\StatusRepository
     */
    private $statusRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * StatusService constructor.
     *
     * @param \App\Repository\StatusRepository        $statusRepository Status repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator        Paginator
     */
    public function __construct(StatusRepository $statusRepository, PaginatorInterface $paginator)
    {
        $this->statusRepository = $statusRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->statusRepository->queryAll(),
            $page,
            StatusRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find status by Id.
     *
     * @param int $id Status Id
     *
     * @return \App\Entity\Status|null Status entity
     */
    public function findOneById(int $id): ?Status
    {
        return $this->statusRepository->findOneById($id);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Status $status): void
    {
        $this->statusRepository->save($status);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Status $status): void
    {
        $this->statusRepository->delete($status);
    }
}
