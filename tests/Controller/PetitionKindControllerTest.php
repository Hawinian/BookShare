<?php
/**
 * PetitionKind Controller test.
 */

namespace App\Tests\Controller;

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
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class PetitionKindControllerTest.
 */
class PetitionKindControllerTest extends WebTestCase
{
    /**
     * Test client.
     */
    private KernelBrowser $httpClient;

    /**
     * Set up tests.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test index route for anonymous user.
     */
    public function testIndexRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;

        // when
        $this->httpClient->request('GET', '/petition_kind');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for admin user.
     */
    public function testIndexRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        // when
        $this->httpClient->request('GET', '/petition_kind');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for non authorized user.
     */
    public function testIndexRouteNonAuthorizedUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        // when
        $this->httpClient->request('GET', '/petition_kind');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test create petition_kind for user.
     */
    public function testCreatePetitionKindUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        // when
        $this->httpClient->request('GET', '/petition_kind/create');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test create petition_kind for admin.
     */
    public function testCreatePetitionKindAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $admin = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($admin);

        // when
        $crawler = $this->httpClient->request('GET', '/petition_kind/create');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('stwórz')->form();
        $form['petition_kind[name]']->setValue('Test PetitionKind');
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Dodawanie powiodło się', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test edit petition_kind for user.
     */
    public function testEditPetitionKindUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        $expectedPetitionKind = new PetitionKind();
        $expectedPetitionKind->setName('Test PetitionKind');
        $petition_kindRepository = self::$container->get(PetitionKindRepository::class);
        $petition_kindRepository->save($expectedPetitionKind);

        // when
        $this->httpClient->request('GET', '/petition_kind/'.$expectedPetitionKind->getId().'/edit');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test edit petition_kind for admin.
     */
    public function testEditPetitionKindAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        $expectedPetitionKind = new PetitionKind();
        $expectedPetitionKind->setName('Test PetitionKind');
        $petition_kindRepository = self::$container->get(PetitionKindRepository::class);
        $petition_kindRepository->save($expectedPetitionKind);

        // when
        $crawler = $this->httpClient->request('GET', '/petition_kind/'.$expectedPetitionKind->getId().'/edit');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('zapisz')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Aktualizacja powiodła się', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test delete petition_kind for user.
     */
    public function testDeletePetitionKindUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        $expectedPetitionKind = new PetitionKind();
        $expectedPetitionKind->setName('Test PetitionKind');
        $petition_kindRepository = self::$container->get(PetitionKindRepository::class);
        $petition_kindRepository->save($expectedPetitionKind);

        // when
        $this->httpClient->request('GET', '/petition_kind/'.$expectedPetitionKind->getId().'/delete');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test delete petition_kind for admin.
     */
    public function testDeletePetitionKindAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        $expectedPetitionKind = new PetitionKind();
        $expectedPetitionKind->setName('Test PetitionKind To Delete');
        $petition_kindRepository = self::$container->get(PetitionKindRepository::class);
        $petition_kindRepository->save($expectedPetitionKind);

        // when
        $crawler = $this->httpClient->request('GET', '/petition_kind/'.$expectedPetitionKind->getId().'/delete');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('usuń')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Usuwanie powiodło się', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test delete petition_kind with books.
     */
    public function testDeletePetitionKindWithBooks(): void
    {
        // given
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        $expectedPetitionKind = new PetitionKind();
        $expectedPetitionKind->setName('Test PetitionKind With Book');
        $petition_kindRepository = self::$container->get(PetitionKindRepository::class);
        $petition_kindRepository->save($expectedPetitionKind);

        $book = $this->createBook();
        $petition = $this->createPetition($book, $adminUser, $expectedPetitionKind);

        // when
        $crawler = $this->httpClient->request('GET', '/petition_kind/'.$expectedPetitionKind->getId().'/delete');
        $form = $crawler->selectButton('usuń')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertStringContainsString('Ten rodzaj prośby jest używany', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Simulate user log in.
     *
     * @param User $user User entity
     */
    private function logIn(User $user): void
    {
        $session = self::$container->get('session');

        $firewallName = 'main';
        $firewallContext = 'main';

        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->httpClient->getCookieJar()->set($cookie);
    }

    /**
     * Create user.
     *
     * @param array $roles User roles
     *
     * @return User User entity
     */
    private function createUser(array $roles): User
    {
        $passwordEncoder = self::$container->get('security.password_encoder');
        $user = new User();
        $user->setEmail('user@example.com');
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
     * Create book.
     */
    private function createBook(): Book
    {
        $image = new UploadedFile(__DIR__.'/../Photo/test.jpg', 'test.jpg');

        $book = new Book();
        $book->setTitle('Test Book');
        $book->setStatus($this->createStatus());
        $book->setImage($image);
        $book->setDate('2021');
        $book->setPublisher($this->createPublisher());
        $book->setDescription('test description');
        $book->setLanguage($this->createLanguage());
        $book->setCover($this->createCover());
        $book->setAuthor($this->createAuthor());
        $book->setCategory($this->createCategory());
        $book->setPages('500');
        $bookRepository = self::$container->get(BookRepository::class);
        $bookRepository->save($book);

        return $book;
    }

    /**
     * Create Petition.
     */
    private function createPetition(Book $book, User $user, PetitionKind $petitionKind): Petition
    {
        $petition = new Petition();
        $petition->setUser($user);
        $petition->setBook($book);
        $petition->setDate(new \DateTime('2012-07-25 17:17:55'));
        $petition->setContent('test content');
        $petition->setPetitionKind($petitionKind);
        $petitionRepository = self::$container->get(PetitionRepository::class);
        $petitionRepository->save($petition);

        return $petition;
    }
}
