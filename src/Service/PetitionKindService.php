<?php
/**
 * PetitionKind service.
 */

namespace App\Service;

use App\Entity\PetitionKind;
use App\Repository\PetitionKindRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PetitionKindService.
 */
class PetitionKindService
{
    /**
     * PetitionKind repository.
     *
     * @var \App\Repository\PetitionKindRepository
     */
    private $petition_kindRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * PetitionKindService constructor.
     *
     * @param \App\Repository\PetitionKindRepository        $petition_kindRepository PetitionKind repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator        Paginator
     */
    public function __construct(PetitionKindRepository $petition_kindRepository, PaginatorInterface $paginator)
    {
        $this->petition_kindRepository = $petition_kindRepository;
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
            $this->petition_kindRepository->queryAll(),
            $page,
            PetitionKindRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find petition_kind by Id.
     *
     * @param int $id PetitionKind Id
     *
     * @return \App\Entity\PetitionKind|null PetitionKind entity
     */
    public function findOneById(int $id): ?PetitionKind
    {
        return $this->petition_kindRepository->findOneById($id);
    }

    /**
     * @param PetitionKind $petition_kind
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(PetitionKind $petition_kind): void
    {
        $this->petition_kindRepository->save($petition_kind);
    }

    /**
     * @param PetitionKind $petition_kind
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(PetitionKind $petition_kind): void
    {
        $this->petition_kindRepository->delete($petition_kind);
    }
}
