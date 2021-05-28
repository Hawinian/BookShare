<?php
/**
 * Language service.
 */

namespace App\Service;

use App\Entity\Language;
use App\Repository\LanguageRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class LanguageService.
 */
class LanguageService
{
    /**
     * Language repository.
     *
     * @var \App\Repository\LanguageRepository
     */
    private $languageRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * LanguageService constructor.
     *
     * @param \App\Repository\LanguageRepository      $languageRepository Language repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator          Paginator
     */
    public function __construct(LanguageRepository $languageRepository, PaginatorInterface $paginator)
    {
        $this->languageRepository = $languageRepository;
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
            $this->languageRepository->queryAll(),
            $page,
            LanguageRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find language by Id.
     *
     * @param int $id Language Id
     *
     * @return \App\Entity\Language|null Language entity
     */
    public function findOneById(int $id): ?Language
    {
        return $this->languageRepository->findOneById($id);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Language $language): void
    {
        $this->languageRepository->save($language);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Language $language): void
    {
        $this->languageRepository->delete($language);
    }
}
