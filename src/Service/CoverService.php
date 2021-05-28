<?php
/**
 * Cover service.
 */

namespace App\Service;

use App\Entity\Cover;
use App\Repository\CoverRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CoverService.
 */
class CoverService
{
    /**
     * Cover repository.
     *
     * @var \App\Repository\CoverRepository
     */
    private $coverRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * CoverService constructor.
     *
     * @param \App\Repository\CoverRepository         $coverRepository Cover repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator       Paginator
     */
    public function __construct(CoverRepository $coverRepository, PaginatorInterface $paginator)
    {
        $this->coverRepository = $coverRepository;
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
            $this->coverRepository->queryAll(),
            $page,
            CoverRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find cover by Id.
     *
     * @param int $id Cover Id
     *
     * @return \App\Entity\Cover|null Cover entity
     */
    public function findOneById(int $id): ?Cover
    {
        return $this->coverRepository->findOneById($id);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Cover $cover): void
    {
        $this->coverRepository->save($cover);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Cover $cover): void
    {
        $this->coverRepository->delete($cover);
    }
}
