<?php
/**
 * Vote service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\VoteRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['category_id']) && is_numeric($filters['category_id'])) {
            $category = $this->categoryService->findOneById(
                $filters['category_id']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }
        if (isset($filters['tag_id']) && is_numeric($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }


        return $resultFilters;
    }

    /**
     * Create paginated list.
     *
     * @param int                                                 $page    Page number
     * @param \Symfony\Component\Security\Core\User\UserInterface $user    User entity
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {

        return $this->paginator->paginate(
            $this->voteRepository->queryAll(),
            $page,
            VoteRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedListCategory(int $page, Category $category): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->voteRepository->queryByCategory($category),
            $page,
            VoteRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
