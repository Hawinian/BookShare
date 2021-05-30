<?php
/**
 * RentalService tests.
 */

namespace App\Tests\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Cover;
use App\Entity\Language;
use App\Entity\Publisher;
use App\Entity\Rental;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\CoverRepository;
use App\Repository\LanguageRepository;
use App\Repository\PublisherRepository;
use App\Repository\RentalRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use App\Service\RentalService;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RentalServiceTest.
 */
class RentalServiceTest extends KernelTestCase
{
    /**
     * Rental service.
     *
     * @var RentalService|object|null
     */
    private ?RentalService $rentalService;

    /**
     * Rental repository.
     *
     * @var RentalRepository|object|null
     */
    private ?RentalRepository $rentalRepository;

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
        $this->rentalRepository = $container->get(RentalRepository::class);
        $this->rentalService = $container->get(RentalService::class);
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
        $expectedRental = new Rental();
        $expectedRental->setUser($this->createUser([User::ROLE_USER], 'user1@example.com'));
        $expectedRental->setBook($this->createBook());
        $expectedRental->setDateOfRental(new \DateTime());
        $date = new \DateTime(); // Y-m-d
        $date->add(new DateInterval('P30D'));
        $expectedRental->setDateOfReturn($date);

        // when
        $this->rentalService->save($expectedRental);
        $resultRental = $this->rentalRepository->findOneById(
            $expectedRental->getId()
        );

        // then
        $this->assertEquals($expectedRental, $resultRental);
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
        $expectedRental = new Rental();
        $book = $this->createBook();
        $user = $this->createUser([User::ROLE_USER], 'user1@example.com');
        $expectedRental = $this->createRental($book, $user);
        $expectedId = $expectedRental->getId();

        // when
        $this->rentalService->delete($expectedRental);
        $result = $this->rentalRepository->findOneById($expectedId);

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
        $expectedRental = new Rental();
        $book = $this->createBook();
        $user = $this->createUser([User::ROLE_USER], 'user1@example.com');
        $expectedRental = $this->createRental($book, $user);

        // when
        $result = $this->rentalService->findOneById($expectedRental->getId());

        // then
        $this->assertEquals($expectedRental->getId(), $result->getId());
    }

    /**
     * Test pagination empty list.
     */
    public function testCreatePaginatedListEmptyList(): void
    {
        // given
        $page = 1;
        $expectedResultSize = 0;
        $user = $this->createUser([User::ROLE_USER], 'user@email.com');

        // when
        $result = $this->rentalService->createPaginatedListAuthor($page, $user);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Test pagination empty list.
     */
    public function testCreatePaginatedListAuthor(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;
        $user = $this->createUser([User::ROLE_USER], 'user@email.com');

        $counter = 0;
        while ($counter < $dataSetSize) {
            $rental = new Rental();
            $rental->setUser($user);
            $rental->setBook($this->createBook());
            $rental->setDateOfRental(new \DateTime());
            $date = new \DateTime(); // Y-m-d
            $date->add(new DateInterval('P30D'));
            $rental->setDateOfReturn($date);
            $this->rentalService->save($rental);

            ++$counter;
        }

        // when
        $result = $this->rentalService->createPaginatedListAuthor($page, $user);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Test pagination empty list.
     */
    public function testCreatePaginatedListInTimeBooks(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;
        $user = $this->createUser([User::ROLE_USER], 'user@email.com');

        $counter = 0;
        while ($counter < $dataSetSize) {
            $rental = new Rental();
            $rental->setUser($user);
            $rental->setBook($this->createBook());
            $rental->setDateOfRental(new \DateTime());
            $date = new \DateTime(); // Y-m-d
            $date->add(new DateInterval('P30D'));
            $rental->setDateOfReturn($date);
            $this->rentalService->save($rental);

            ++$counter;
        }

        // when
        $result = $this->rentalService->createPaginatedListInTimeBooks($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Test pagination empty list.
     */
    public function testCreatePaginatedAllLateBooks(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;
        $user = $this->createUser([User::ROLE_USER], 'user@email.com');

        $counter = 0;
        while ($counter < $dataSetSize) {
            $rental = new Rental();
            $rental->setUser($user);
            $rental->setBook($this->createBook());
            $rental->setDateOfRental(new \DateTime());
            $rental->setDateOfReturn(new \DateTime('2020-03-11 16:45:52'));
            $this->rentalService->save($rental);

            ++$counter;
        }

        // when
        $result = $this->rentalService->createPaginatedListLateBooks($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    /**
     * Create user.
     *
     * @param array $roles User roles
     *
     * @return User User entity
     */
    private function createUser(array $roles, string $email): User
    {
        $passwordEncoder = self::$container->get('security.password_encoder');
        $user = new User();
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setLogin('user');
        $user->setStatus(100);
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                'p@55w0rd'
            )
        );
        $userRepository = self::$container->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
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

    /**
     * Create Rental.
     */
    private function createRental(Book $book, User $user): Rental
    {
        $rental = new Rental();
        $rental->setUser($user);
        $rental->setBook($book);
        $rental->setDateOfRental(new \DateTime());
        $date = new \DateTime(); // Y-m-d
        $date->add(new DateInterval('P30D'));
        $rental->setDateOfReturn($date);
        $rentalRepository = self::$container->get(RentalRepository::class);
        $rentalRepository->save($rental);

        return $rental;
    }
}
