<?php
/**
 * Giveback service.
 */

namespace App\Service;

use App\Entity\Giveback;
use App\Repository\GivebackRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class GivebackService.
 */
class GivebackService
{
    /**
     * Giveback repository.
     *
     * @var \App\Repository\GivebackRepository
     */
    private $givebackRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * GivebackService constructor.
     *
     * @param \App\Repository\GivebackRepository      $givebackRepository Giveback repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator          Paginator
     */
    public function __construct(GivebackRepository $givebackRepository, PaginatorInterface $paginator)
    {
        $this->givebackRepository = $givebackRepository;
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
            $this->givebackRepository->queryAll(),
            $page,
            GivebackRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find giveback by Id.
     *
     * @param int $id Giveback Id
     *
     * @return \App\Entity\Giveback|null Giveback entity
     */
    public function findOneById(int $id): ?Giveback
    {
        return $this->givebackRepository->findOneById($id);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Giveback $giveback): void
    {
        $this->givebackRepository->save($giveback);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Giveback $giveback): void
    {
        $this->givebackRepository->delete($giveback);
    }
}
