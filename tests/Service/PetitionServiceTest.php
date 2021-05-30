<?php
/**
 * PetitionService tests.
 */

namespace App\Tests\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Cover;
use App\Entity\Language;
use App\Entity\Petition;
use App\Entity\PetitionKind;
use App\Entity\Publisher;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\CoverRepository;
use App\Repository\LanguageRepository;
use App\Repository\PetitionKindRepository;
use App\Repository\PetitionRepository;
use App\Repository\PublisherRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use App\Service\PetitionService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class PetitionServiceTest.
 */
class PetitionServiceTest extends KernelTestCase
{
    /**
     * Petition service.
     *
     * @var PetitionService|object|null
     */
    private ?PetitionService $petitionService;

    /**
     * Petition repository.
     *
     * @var PetitionRepository|object|null
     */
    private ?PetitionRepository $petitionRepository;

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
        $this->petitionRepository = $container->get(PetitionRepository::class);
        $this->petitionService = $container->get(PetitionService::class);
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
        $expectedPetition = new Petition();
        $expectedPetition->setUser($this->createUser([User::ROLE_USER], 'user1@example.com'));
        $expectedPetition->setBook($this->createBook());
        $expectedPetition->setPetitionKind($this->createPetitionKind());
        $expectedPetition->setDate(new \DateTime());
        $expectedPetition->setContent('Test content');

        // when
        $this->petitionService->save($expectedPetition);
        $resultPetition = $this->petitionRepository->findOneById(
            $expectedPetition->getId()
        );

        // then
        $this->assertEquals($expectedPetition, $resultPetition);
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
        $expectedPetition = new Petition();
        $book = $this->createBook();
        $user = $this->createUser([User::ROLE_USER], 'user1@example.com');
        $expectedPetition = $this->createPetition($book, $user);
        $expectedId = $expectedPetition->getId();

        // when
        $this->petitionService->delete($expectedPetition);
        $result = $this->petitionRepository->findOneById($expectedId);

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
        $expectedPetition = new Petition();
        $book = $this->createBook();
        $user = $this->createUser([User::ROLE_USER], 'user1@example.com');
        $expectedPetition = $this->createPetition($book, $user);

        // when
        $result = $this->petitionService->findOneById($expectedPetition->getId());

        // then
        $this->assertEquals($expectedPetition->getId(), $result->getId());
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
        $result = $this->petitionService->createPaginatedList($page);

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
            $petition = new Petition();
            $petition->setUser($this->createUser([User::ROLE_USER], 'user'.$counter.'email.com'));
            $petition->setBook($this->createBook());
            $petition->setPetitionKind($this->createPetitionKind());
            $petition->setDate(new \DateTime());
            $petition->setContent('Test content');
            $this->petitionService->save($petition);

            ++$counter;
        }

        // when
        $result = $this->petitionService->createPaginatedList($page);

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
     * Create PetitionKind.
     */
    private function createPetitionKind(): PetitionKind
    {
        $petition_kind = new PetitionKind();
        $petition_kind->setName('Test PetitionKind');
        $petition_kindRepository = self::$container->get(PetitionKindRepository::class);
        $petition_kindRepository->save($petition_kind);

        return $petition_kind;
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
     * Create Petition.
     */
    private function createPetition(Book $book, User $user): Petition
    {
        $petition = new Petition();
        $petition->setUser($user);
        $petition->setBook($book);
        $petition->setDate(new \DateTime('2012-07-25 17:17:55'));
        $petition->setContent('test content');
        $petition->setPetitionKind($this->createPetitionKind());
        $petitionRepository = self::$container->get(PetitionRepository::class);
        $petitionRepository->save($petition);

        return $petition;
    }
}
