<?php
/**
 * Author service.
 */

namespace App\Service;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AuthorService.
 */
class AuthorService
{
    /**
     * Author repository.
     *
     * @var \App\Repository\AuthorRepository
     */
    private $authorRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * AuthorService constructor.
     *
     * @param \App\Repository\AuthorRepository        $authorRepository Author repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator        Paginator
     */
    public function __construct(AuthorRepository $authorRepository, PaginatorInterface $paginator)
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
            AuthorRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find author by Id.
     *
     * @param int $id Author Id
     *
     * @return \App\Entity\Author|null Author entity
     */
    public function findOneById(int $id): ?Author
    {
        return $this->authorRepository->findOneById($id);
    }
}
