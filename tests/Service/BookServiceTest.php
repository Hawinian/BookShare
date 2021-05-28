<?php
/**
 * BookService tests.
 */

namespace App\Tests\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Cover;
use App\Entity\Language;
use App\Entity\Publisher;
use App\Entity\Status;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\CoverRepository;
use App\Repository\LanguageRepository;
use App\Repository\PublisherRepository;
use App\Repository\StatusRepository;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class BookServiceTest.
 */
class BookServiceTest extends KernelTestCase
{
    /**
     * Book service.
     *
     * @var BookService|object|null
     */
    private ?BookService $bookService;

    /**
     * Book repository.
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
        $this->bookRepository = $container->get(BookRepository::class);
        $this->bookService = $container->get(BookService::class);
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
        $expectedBook = new Book();
        $expectedBook->setTitle('Test Book');
        $expectedBook->setStatus($this->createStatus());
        $expectedBook->setImage('Test image');
        $expectedBook->setDate('2021');
        $expectedBook->setPublisher($this->createPublisher());
        $expectedBook->setDescription('test description');
        $expectedBook->setLanguage($this->createLanguage());
        $expectedBook->setCover($this->createCover());
        $expectedBook->setAuthor($this->createAuthor());
        $expectedBook->setCategory($this->createCategory());
        $expectedBook->setPages('500');

        // when
        $this->bookService->save($expectedBook);
        $resultBook = $this->bookRepository->findOneById(
            $expectedBook->getId()
        );

        // then
        $this->assertEquals($expectedBook, $resultBook);
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
        $expectedBook = $this->createBook();
        $this->bookRepository->save($expectedBook);
        $expectedId = $expectedBook->getId();

        // when
        $this->bookService->delete($expectedBook);
        $result = $this->bookRepository->findOneById($expectedId);

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
        $expectedBook = $this->createBook();
        $this->bookRepository->save($expectedBook);

        // when
        $result = $this->bookService->findOneById($expectedBook->getId());

        // then
        $this->assertEquals($expectedBook->getId(), $result->getId());
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
        $result = $this->bookService->createPaginatedList($page);

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
            $book = new Book();
            $book->setTitle('Test Book #'.$counter);
            $book->setStatus($this->createStatus());
            $book->setImage('Test image');
            $book->setDate('2021');
            $book->setPublisher($this->createPublisher());
            $book->setDescription('test description');
            $book->setLanguage($this->createLanguage());
            $book->setCover($this->createCover());
            $book->setAuthor($this->createAuthor());
            $book->setCategory($this->createCategory());
            $book->setPages('500');
            $this->bookService->save($book);

            ++$counter;
        }

        // when
        $result = $this->bookService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Test pagination empty list.
     */
    public function testCreatePaginatedListForRanking(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $book = new Book();
            $book->setTitle('Test Book #'.$counter);
            $book->setStatus($this->createStatus());
            $book->setImage('Test image');
            $book->setDate('2021');
            $book->setPublisher($this->createPublisher());
            $book->setDescription('test description');
            $book->setLanguage($this->createLanguage());
            $book->setCover($this->createCover());
            $book->setAuthor($this->createAuthor());
            $book->setCategory($this->createCategory());
            $book->setPages('500');
            $this->bookService->save($book);

            ++$counter;
        }

        // when
        $result = $this->bookService->createPaginatedListForRanking($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Create Status.
     */
    private function createStatus(): Status
    {
        $status = new Status();
        $status->setName('Test Status');
        $statusRepository = self::$container->get(StatusRepository::class);
        $statusRepository->save($status);

        return $status;
    }

    /**
     * Create Publisher.
     */
    private function createPublisher(): Publisher
    {
        $publisher = new Publisher();
        $publisher->setName('Test Publisher');
        $publisherRepository = self::$container->get(PublisherRepository::class);
        $publisherRepository->save($publisher);

        return $publisher;
    }

    /**
     * Create Auhtor.
     */
    private function createAuthor(): Author
    {
        $author = new Author();
        $author->setName('Test Author');
        $authorRepository = self::$container->get(AuthorRepository::class);
        $authorRepository->save($author);

        return $author;
    }

    /**
     * Create Language.
     */
    private function createLanguage(): Language
    {
        $language = new Language();
        $language->setName('Test Language');
        $languageRepository = self::$container->get(LanguageRepository::class);
        $languageRepository->save($language);

        return $language;
    }

    /**
     * Create Cover.
     */
    private function createCover(): Cover
    {
        $cover = new Cover();
        $cover->setName('Test Cover');
        $coverRepository = self::$container->get(CoverRepository::class);
        $coverRepository->save($cover);

        return $cover;
    }

    /**
     * Create Category.
     */
    private function createCategory(): Category
    {
        $category = new Category();
        $category->setName('Test Category');
        $categoryRepository = self::$container->get(CategoryRepository::class);
        $categoryRepository->save($category);

        return $category;
    }

    /**
     * Create book.
     */
    private function createBook(): Book
    {
        $book = new Book();
        $book->setTitle('Test Book');
        $book->setStatus($this->createStatus());
        $book->setImage('Test image');
        $book->setDate('2021');
        $book->setPublisher($this->createPublisher());
        $book->setDescription('test description');
        $book->setLanguage($this->createLanguage());
        $book->setCover($this->createCover());
        $book->setAuthor($this->createAuthor());
        $book->setCategory($this->createCategory());
        $book->setPages('500');
        $book->setTitle('Test Book');
        $bookRepository = self::$container->get(BookRepository::class);
        $bookRepository->save($book);

        return $book;
    }
}
