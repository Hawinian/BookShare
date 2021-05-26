<?php
/**
 * AuthorService tests.
 */

namespace App\Tests\Service;

use App\Entity\Author;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use App\Service\AuthorService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class AuthorServiceTest.
 */
class AuthorServiceTest extends KernelTestCase
{
    /**
     * Author service.
     *
     * @var AuthorService|object|null
     */
    private ?AuthorService $authorService;

    /**
     * Author repository.
     *
     * @var AuthorRepository|object|null
     */
    private ?AuthorRepository $authorRepository;

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
        $this->authorRepository = $container->get(AuthorRepository::class);
        $this->authorService = $container->get(AuthorService::class);
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
        $expectedAuthor = new Author();
        $expectedAuthor->setName('Test Author');

        // when
        $this->authorService->save($expectedAuthor);
        $resultAuthor = $this->authorRepository->findOneById(
            $expectedAuthor->getId()
        );

        // then
        $this->assertEquals($expectedAuthor, $resultAuthor);
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
        $expectedAuthor = new Author();
        $expectedAuthor->setName('Test Author');
        $this->authorRepository->save($expectedAuthor);
        $expectedId = $expectedAuthor->getId();

        // when
        $this->authorService->delete($expectedAuthor);
        $result = $this->authorRepository->findOneById($expectedId);

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
        $expectedAuthor = new Author();
        $expectedAuthor->setName('Test Author');
        $this->authorRepository->save($expectedAuthor);

        // when
        $result = $this->authorService->findOneById($expectedAuthor->getId());

        // then
        $this->assertEquals($expectedAuthor->getId(), $result->getId());
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
        $result = $this->authorService->createPaginatedList($page);

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
            $author = new Author();
            $author->setName('Test Author #'.$counter);
            $this->authorService->save($author);

            ++$counter;
        }

        // when
        $result = $this->authorService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    // other tests for paginated list
}
