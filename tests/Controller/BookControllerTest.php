<?php
/**
 * Book Controller test.
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
use App\Entity\Rental;
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
use App\Repository\RentalRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class BookControllerTest.
 */
class BookControllerTest extends WebTestCase
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
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', '/');
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
        $this->httpClient->request('GET', '/');
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
        $expectedStatusCode = 200;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        // when
        $this->httpClient->request('GET', '/');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for anonymous user.
     */
    public function testRankingRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', '/ranking');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for user.
     */
    public function testRankingRouteUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        // when
        $this->httpClient->request('GET', '/ranking');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

//    /**
//     * Test index route for anonymous user.
//     */
//    public function testSearchRouteAnonymousUser(): void
//    {
//        // given
//        $expectedStatusCode = 200;
//
//        // when
//        $this->httpClient->request('GET', '/search');
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals($expectedStatusCode, $resultStatusCode);
//    }

//    /**
//     * Test index route for user.
//     */
//    public function testSearchRouteUser(): void
//    {
//        // given
//        $expectedStatusCode = 200;
//        $user = $this->createUser([User::ROLE_USER]);
//        $this->logIn($user);
//
//        // when
//        $this->httpClient->request('GET', '/search');
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals($expectedStatusCode, $resultStatusCode);
//    }

    /**
     * Test create book for user.
     */
    public function testCreateBookUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        // when
        $this->httpClient->request('GET', '/create');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test create book for admin.
     */
    public function testCreateBookAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $admin = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($admin);
        $category = $this->createCategory();
        $status = $this->createStatus();
        $publisher = $this->createPublisher();
        $language = $this->createLanguage();
        $cover = $this->createCover();
        $author = $this->createAuthor();
        $photo = new UploadedFile(__DIR__.'/../Photo/test.jpg', 'test.jpg');

        // when
        $crawler = $this->httpClient->request('GET', '/create');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('stw??rz')->form();
        $form['book[title]']->setValue('Test Book');
        $form['book[image]']->upload($photo);
        $form['book[date]']->setValue('2021');
        $form['book[pages]']->setValue('500');
        $form['book[description]']->setValue('test description');
        $form['book[category]']->select($category->getId());
        $form['book[status]']->select($status->getId());
        $form['book[publisher]']->select($publisher->getId());
        $form['book[language]']->select($language->getId());
        $form['book[cover]']->select($cover->getId());
        $form['book[author]']->select($author->getId());
        $form['book[tag]']->setValue('nowy, tag, testowy');
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Dodawanie powiod??o si??', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test edit book for user.
     */
    public function testEditBookUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        $expectedBook = $this->createBook();

        // when
        $this->httpClient->request('GET', '/'.$expectedBook->getId().'/edit');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test edit book for admin.
     */
    public function testEditBookAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        $expectedBook = $this->createBook();
        $photo = new UploadedFile(__DIR__.'/../Photo/test.jpg', 'test.jpg');

        // when
        $crawler = $this->httpClient->request('GET', '/'.$expectedBook->getId().'/edit');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('zapisz')->form();
        $form['book[image]']->upload($photo);
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Aktualizacja powiod??a si??', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test delete book for user.
     */
    public function testDeleteBookUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        $expectedBook = $this->createBook();

        // when
        $this->httpClient->request('GET', '/'.$expectedBook->getId().'/delete');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test delete book for admin.
     */
    public function testDeleteBookAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        $expectedBook = $this->createBook();

        // when
        $crawler = $this->httpClient->request('GET', '/'.$expectedBook->getId().'/delete');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('usu??')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Usuwanie powiod??o si??', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test delete book with rentals.
     */
    public function testDeleteBookWithRentals(): void
    {
        // given
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        $expectedBook = $this->createBook();
        $this->createRental($expectedBook, $adminUser);

        // when
        $crawler = $this->httpClient->request('GET', '/'.$expectedBook->getId().'/delete');
        $form = $crawler->selectButton('usu??')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertStringContainsString('Ksi????ka jest wypo??yczona lub zarezerwowana', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test delete book with petitions.
     */
    public function testDeleteBookWithPetitions(): void
    {
        // given
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        $expectedBook = $this->createBook();
        $this->createPetition($expectedBook, $adminUser);

        // when
        $crawler = $this->httpClient->request('GET', '/'.$expectedBook->getId().'/delete');
        $form = $crawler->selectButton('usu??')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertStringContainsString('Ksi????ka jest wypo??yczona lub zarezerwowana', $this->httpClient->getResponse()->getContent());
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
