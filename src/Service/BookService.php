<?php
/**
 * Book service.
 */

namespace App\Service;

use App\Entity\Book;
use App\Entity\Category;
use App\Repository\BookRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BookService.
 */
class BookService
{
    /**
     * Book repository.
     *
     * @var \App\Repository\BookRepository
     */
    private $bookRepository;

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
     * Author service.
     *
     * @var \App\Service\AuthorService
     */
    private $authorService;

    /**
     * Language service.
     *
     * @var \App\Service\LanguageService
     */
    private $languageService;

    /**
     * Tag service.
     *
     * @var \App\Service\TagService
     */
    private $tagService;

    /**
     * BookService constructor.
     *
     * @param \App\Repository\BookRepository          $bookRepository Book repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator      Paginator
     */
    public function __construct(BookRepository $bookRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService, LanguageService $languageService, AuthorService $authorService)
    {
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
        $this->languageService = $languageService;
        $this->authorService = $authorService;
    }

    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->bookRepository->queryAll($filters),
            $page,
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function createPaginatedListForRanking(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->bookRepository->queryAllRanking($filters),
            $page,
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Book $book): void
    {
        $this->bookRepository->save($book);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Book $book): void
    {
        $this->bookRepository->delete($book);
    }

    /**
     * Find book by Id.
     *
     * @param int $id Book Id
     *
     * @return \App\Entity\Book|null Book entity
     */
    public function findOneById(int $id): ?Book
    {
        return $this->bookRepository->findOneById($id);
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
        if (isset($filters['language_id']) && is_numeric($filters['language_id'])) {
            $language = $this->languageService->findOneById(
                $filters['language_id']
            );
            if (null !== $language) {
                $resultFilters['language'] = $language;
            }
        }
        if (isset($filters['author_id']) && is_numeric($filters['author_id'])) {
            $author = $this->authorService->findOneById(
                $filters['author_id']
            );
            if (null !== $author) {
                $resultFilters['author'] = $author;
            }
        }
        if (isset($filters['tag_id']) && is_numeric($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }
        if (isset($filters['title'])) {
            $resultFilters['title'] = $filters['title'];
        }

        return $resultFilters;
    }
}
