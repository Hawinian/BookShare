<?php
/**
 * Giveback Controller test.
 */

namespace App\Tests\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Cover;
use App\Entity\Giveback;
use App\Entity\Language;
use App\Entity\Publisher;
use App\Entity\Rental;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\CoverRepository;
use App\Repository\GivebackRepository;
use App\Repository\LanguageRepository;
use App\Repository\PublisherRepository;
use App\Repository\RentalRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class GivebackControllerTest.
 */
class GivebackControllerTest extends WebTestCase
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
        $this->httpClient->request('GET', '/giveback');
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
        $this->httpClient->request('GET', '/giveback');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for user.
     */
    public function testIndexRouteUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        // when
        $this->httpClient->request('GET', '/giveback');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test accept giveback for user.
     */
    public function testAcceptGivebackUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);
        $book = $this->createBook();

        $book = $this->createBook();
        $rental = $this->createRental($book, $user);
        $giveback = $this->createGiveback($rental);

        // when
        $this->httpClient->request('GET', '/giveback/'.$giveback->getId().'/accept');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test accept giveback for admin.
     */
    public function testAcceptGivebackAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);
        $book = $this->createBook();

        $book = $this->createBook();
        $rental = $this->createRental($book, $adminUser);
        $giveback = $this->createGiveback($rental);

        // when
        $crawler = $this->httpClient->request('GET', '/giveback/'.$giveback->getId().'/accept');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('zaakceptuj')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Usuwanie powiodło się', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test delete giveback for user.
     */
    public function testRejectGivebackUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);
        $book = $this->createBook();

        $book = $this->createBook();
        $rental = $this->createRental($book, $user);
        $giveback = $this->createGiveback($rental);

        // when
        $this->httpClient->request('GET', '/giveback/'.$giveback->getId().'/reject');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test delete giveback for admin.
     */
    public function testRejectGivebackAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);
        $book = $this->createBook();

        $book = $this->createBook();
        $rental = $this->createRental($book, $adminUser);
        $giveback = $this->createGiveback($rental);

        // when
        $crawler = $this->httpClient->request('GET', '/giveback/'.$giveback->getId().'/reject');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('odrzuć')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Usuwanie powiodło się', $this->httpClient->getResponse()->getContent());
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
     * Create Rental.
     */
    private function createRental(Book $book, User $user): Rental
    {
        $rental = new Rental();
        $rental->setUser($user);
        $rental->setBook($book);
        $rental->setDateOfReturn(new \DateTime('2012-07-25 17:17:55'));
        $rental->setDateOfRental(new \DateTime('2012-08-25 17:17:55'));
        $rentalRepository = self::$container->get(RentalRepository::class);
        $rentalRepository->save($rental);

        return $rental;
    }

    /**
     * Create Giveback.
     */
    private function createGiveback(Rental $rental): Giveback
    {
        $giveback = new Giveback();
        $giveback->setRental($rental);
        $giveback->setDate(new \DateTime());
        $giveback->setContent('test content');
        $givebackRepository = self::$container->get(GivebackRepository::class);
        $givebackRepository->save($giveback);

        return $giveback;
    }
}
