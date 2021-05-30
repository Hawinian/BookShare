<?php
/**
 * Vote service.
 */

namespace App\Service;

use App\Entity\Vote;
use App\Repository\VoteRepository;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class VoteService.
 */
class VoteService
{
    /**
     * Vote repository.
     *
     * @var \App\Repository\VoteRepository
     */
    private $voteRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * Category service.
     *
     * @var \App\Service\CategoryService
     */
    private $categoryService;

    /**
     * Tag service.
     *
     * @var \App\Service\TagService
     */
    private $tagService;

    /**
     * VoteService constructor.
     *
     * @param \App\Repository\VoteRepository          $voteRepository Vote repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator      Paginator
     */
    public function __construct(VoteRepository $voteRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService)
    {
        $this->voteRepository = $voteRepository;
        $this->paginator = $paginator;
    }

    /**
     * Find vote by Id.
     *
     * @param int $id Vote Id
     *
     * @return \App\Entity\Vote|null Vote entity
     */
    public function findOneById(int $id): ?Vote
    {
        return $this->voteRepository->findOneById($id);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Vote $vote): void
    {
        $this->voteRepository->save($vote);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Vote $vote): void
    {
        $this->voteRepository->delete($vote);
    }
}
