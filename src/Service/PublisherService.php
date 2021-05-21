<?php
/**
 * Publisher service.
 */

namespace App\Service;

use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PublisherService.
 */
class PublisherService
{
    /**
     * Publisher repository.
     *
     * @var \App\Repository\PublisherRepository
     */
    private $authorRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * PublisherService constructor.
     *
     * @param \App\Repository\PublisherRepository        $authorRepository Publisher repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator        Paginator
     */
    public function __construct(PublisherRepository $authorRepository, PaginatorInterface $paginator)
    {
        $this->authorRepository = $authorRepository;
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
            $this->authorRepository->queryAll(),
            $page,
            PublisherRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find author by Id.
     *
     * @param int $id Publisher Id
     *
     * @return \App\Entity\Publisher|null Publisher entity
     */
    public function findOneById(int $id): ?Publisher
    {
        return $this->authorRepository->findOneById($id);
    }
}
