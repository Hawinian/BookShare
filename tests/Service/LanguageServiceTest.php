<?php
/**
 * LanguageService tests.
 */

namespace App\Tests\Service;

use App\Entity\Language;
use App\Repository\BookRepository;
use App\Repository\LanguageRepository;
use App\Service\LanguageService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class LanguageServiceTest.
 */
class LanguageServiceTest extends KernelTestCase
{
    /**
     * Language service.
     *
     * @var LanguageService|object|null
     */
    private ?LanguageService $languageService;

    /**
     * Language repository.
     *
     * @var LanguageRepository|object|null
     */
    private ?LanguageRepository $languageRepository;

    /**
     * Task repository.
     *
     * @var BookRepository|object|null
     */
    private ?BookRepository $bookRepository;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $this->languageRepository = $container->get(LanguageRepository::class);
        $this->languageService = $container->get(LanguageService::class);
        $this->bookRepository = $container->get(BookRepository::class);
    }

    /**
     * Test save.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testSave(): void
    {
        // given
        $expectedLanguage = new Language();
        $expectedLanguage->setName('Test Language');

        // when
        $this->languageService->save($expectedLanguage);
        $resultLanguage = $this->languageRepository->findOneById(
            $expectedLanguage->getId()
        );

        // then
        $this->assertEquals($expectedLanguage, $resultLanguage);
    }

    /**
     * Test delete.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testDelete(): void
    {
        // given
        $expectedLanguage = new Language();
        $expectedLanguage->setName('Test Language');
        $this->languageRepository->save($expectedLanguage);
        $expectedId = $expectedLanguage->getId();

        // when
        $this->languageService->delete($expectedLanguage);
        $result = $this->languageRepository->findOneById($expectedId);

        // then
        $this->assertNull($result);
    }

    /**
     * Test find by id.
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testFindById(): void
    {
        // given
        $expectedLanguage = new Language();
        $expectedLanguage->setName('Test Language');
        $this->languageRepository->save($expectedLanguage);

        // when
        $result = $this->languageService->findOneById($expectedLanguage->getId());

        // then
        $this->assertEquals($expectedLanguage->getId(), $result->getId());
    }

    /**
     * Test pagination empty list.
     */
    public function testCreatePaginatedListEmptyList(): void
    {
        // given
        $page = 1;
        $expectedResultSize = 0;

        // when
        $result = $this->languageService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Test pagination empty list.
     */
    public function testCreatePaginatedList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $language = new Language();
            $language->setName('Test Language #'.$counter);
            $this->languageService->save($language);

            ++$counter;
        }

        // when
        $result = $this->languageService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    // other tests for paginated list
}
