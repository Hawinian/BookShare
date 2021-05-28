<?php
/**
 * Petition service.
 */

namespace App\Service;

use App\Entity\Petition;
use App\Repository\PetitionRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PetitionService.
 */
class PetitionService
{
    /**
     * Petition repository.
     *
     * @var \App\Repository\PetitionRepository
     */
    private $petitionRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * PetitionService constructor.
     *
     * @param \App\Repository\PetitionRepository     $petitionRepository Petition repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator           Paginator
     */
    public function __construct(PetitionRepository $petitionRepository, PaginatorInterface $paginator)
    {
        $this->petitionRepository = $petitionRepository;
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
            $this->petitionRepository->queryAll(),
            $page,
            PetitionRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find petition by Id.
     *
     * @param int $id Petition Id
     *
     * @return \App\Entity\Petition|null Petition entity
     */
    public function findOneById(int $id): ?Petition
    {
        return $this->petitionRepository->findOneById($id);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Petition $petition): void
    {
        $this->petitionRepository->save($petition);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Petition $petition): void
    {
        $this->petitionRepository->delete($petition);
    }
}
