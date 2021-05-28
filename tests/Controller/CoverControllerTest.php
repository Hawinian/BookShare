<?php
/**
 * Cover Controller test.
 */

namespace App\Tests\Controller;

use App\Entity\Author;
use App\Entity\Cover;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Language;
use App\Entity\Publisher;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\AuthorRepository;
use App\Repository\CoverRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\PublisherRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class CoverControllerTest.
 */
class CoverControllerTest extends WebTestCase
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
        $this->httpClient->request('GET', '/cover');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for admin.
     */
    public function testIndexRouteAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        // when
        $this->httpClient->request('GET', '/cover');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for user.
     */
    public function testIndexRoutedUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        // when
        $this->httpClient->request('GET', '/cover');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }


    /**
     * Test create cover for user.
     */
    public function testCreateCoverUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        // when
        $this->httpClient->request('GET', '/cover/create');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test create cover for admin.
     */
    public function testCreateCoverAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $admin = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($admin);

        // when
        $crawler = $this->httpClient->request('GET', '/cover/create');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('stwórz')->form();
        $form['cover[name]']->setValue('Test Cover');
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Dodawanie powiodło się', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test edit cover for user.
     */
    public function testEditCoverUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        $expectedCover = new Cover();
        $expectedCover->setName('Test Cover');
        $coverRepository = self::$container->get(CoverRepository::class);
        $coverRepository->save($expectedCover);

        // when
        $this->httpClient->request('GET', '/cover/'.$expectedCover->getId().'/edit');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test edit cover for admin.
     */
    public function testEditCoverAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        $expectedCover = new Cover();
        $expectedCover->setName('Test Cover');
        $coverRepository = self::$container->get(CoverRepository::class);
        $coverRepository->save($expectedCover);

        // when
        $crawler = $this->httpClient->request('GET', '/cover/'.$expectedCover->getId().'/edit');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('zapisz')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Aktualizacja powiodła się', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test delete cover for user.
     */
    public function testDeleteCoverUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        $expectedCover = new Cover();
        $expectedCover->setName('Test Cover');
        $coverRepository = self::$container->get(CoverRepository::class);
        $coverRepository->save($expectedCover);

        // when
        $this->httpClient->request('GET', '/cover/'.$expectedCover->getId().'/delete');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test delete cover for admin.
     */
    public function testDeleteCoverAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        $expectedCover = new Cover();
        $expectedCover->setName('Test Cover To Delete');
        $coverRepository = self::$container->get(CoverRepository::class);
        $coverRepository->save($expectedCover);

        // when
        $crawler = $this->httpClient->request('GET', '/cover/'.$expectedCover->getId().'/delete');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('usuń')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Usuwanie powiodło się', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test delete cover with books.
     */
    public function testDeleteCoverWithBooks(): void
    {
        // given
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        $expectedCover = new Cover();
        $expectedCover->setName('Test Cover With Book');
        $coverRepository = self::$container->get(CoverRepository::class);
        $coverRepository->save($expectedCover);

        $book = new Book();
        $book->setStatus($this->createStatus());
        $book->setImage('Test image');
        $book->setDate('2021');
        $book->setPublisher($this->createPublisher());
        $book->setDescription('test description');
        $book->setLanguage($this->createLanguage());
        $book->setAuthor($this->createAuthor());
        $book->setCategory($this->createCategory());
        $book->setCover($expectedCover);
        $book->setPages('500');
        $book->setTitle('Test Book');
        $bookRepository = self::$container->get(BookRepository::class);
        $bookRepository->save($book);

        // when
        $crawler = $this->httpClient->request('GET', '/cover/'.$expectedCover->getId().'/delete');
        $form = $crawler->selectButton('usuń')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertStringContainsString('Okładka zawiera inne obiekty', $this->httpClient->getResponse()->getContent());
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
     *
     * @return Status
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
     *
     * @return Publisher
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
     *
     * @return Category
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
     *
     * @return Language
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
     * Create Auhtor.
     *
     * @return Author
     */
    private function createAuthor(): Author
    {
        $author = new Author();
        $author->setName('Test Author');
        $authorRepository = self::$container->get(AuthorRepository::class);
        $authorRepository->save($author);

        return $author;
    }


}
