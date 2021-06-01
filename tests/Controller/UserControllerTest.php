<?php
/**
 * User Controller test.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class UserControllerTest.
 */
class UserControllerTest extends WebTestCase
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
        $this->httpClient->request('GET', '/user');
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
        $this->httpClient->request('GET', '/user');
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
        $this->httpClient->request('GET', '/user');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for anonymous user.
     */
    public function testRegisterRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', '/user/register');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for user.
     */
    public function testRegisterRouteUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        // when
        $this->httpClient->request('GET', '/user/register');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for admin.
     */
    public function testRegisterRouteAdmin(): void
    {
        // given
        $expectedStatusCode = 302;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        // when
        $this->httpClient->request('GET', '/user/register');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for anonymous user.
     */
    public function testEditDataRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);

        // when
        $this->httpClient->request('GET', '/user/'.$adminUser->getId().'/edit-data');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for anonymous user.
     */
    public function testEditPasswordRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);

        // when
        $this->httpClient->request('GET', '/user/'.$adminUser->getId().'/edit-password');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for anonymous user.
     */
    public function testAdminEditDataRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $user = $this->createUser([User::ROLE_USER]);

        // when
        $this->httpClient->request('GET', '/user/'.$user->getId().'/admin-edit-data');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for anonymous user.
     */
    public function testAdminEditPasswordRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);

        // when
        $this->httpClient->request('GET', '/user/'.$adminUser->getId().'/admin-edit-password');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

//    /**
//     * Test index route for anonymous user.
//     */
//    public function testDeleteRouteAnonymousUser(): void
//    {
//        // given
//        $expectedStatusCode = 302;
//        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
//
//        // when
//        $this->httpClient->request('GET', '/user/'.$adminUser->getId().'/delete');
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//
//        // then
//        $this->assertEquals($expectedStatusCode, $resultStatusCode);
//    }

    /**
     * Test index route for anonymous user.
     */
    public function testAdminIndexRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);

        // when
        $this->httpClient->request('GET', '/user/admin');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for admin.
     */
    public function testAdminIndexRouteAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);

        // when
        $this->httpClient->request('GET', '/user/admin');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test index route for admin.
     */
    public function testAdminIndexRouteUser(): void
    {
        // given
        $expectedStatusCode = 302;
        $adminUser = $this->createUser([User::ROLE_USER]);
        $this->logIn($adminUser);

        // when
        $this->httpClient->request('GET', '/user/admin');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test create user for admin.
     */
    public function testRegister(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $crawler = $this->httpClient->request('GET', '/user/register');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('Zarejestruj')->form();
        $user = new User();
        $form['user[email]']->setValue('example@user.com');
        $form['user[login]']->setValue('tester');
        $form['user[plainPassword][first]']->setValue('tester');
        $form['user[plainPassword][second]']->setValue('tester');
        $user->setStatus(100);
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Dodawanie powiodło się', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test create user for admin.
     */
    public function testEditDataUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        // when
        $crawler = $this->httpClient->request('GET', '/user/'.$user->getId().'/edit-data');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('zapisz')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Aktualizacja powiodła się', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test create user for admin.
     */
    public function testEditPasswordUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $user = $this->createUser([User::ROLE_USER]);
        $this->logIn($user);

        // when
        $crawler = $this->httpClient->request('GET', '/user/'.$user->getId().'/edit-password');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('zapisz')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Aktualizacja powiodła się', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test create user for admin.
     */
    public function testAdminEditDataAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createAdmin([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);
        $user = $this->createUser([User::ROLE_USER]);

        // when
        $crawler = $this->httpClient->request('GET', '/user/'.$user->getId().'/admin-edit-data');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('zapisz')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Aktualizacja powiodła się', $this->httpClient->getResponse()->getContent());
    }

    /**
     * Test create user for admin.
     */
    public function testAdminEditPasswordAdmin(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createAdmin([User::ROLE_USER, User::ROLE_ADMIN]);
        $this->logIn($adminUser);
        $user = $this->createUser([User::ROLE_USER]);

        // when
        $crawler = $this->httpClient->request('GET', '/user/'.$user->getId().'/admin-edit-password');
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
        $form = $crawler->selectButton('zapisz')->form();
        $this->httpClient->submit($form);
        $this->httpClient->followRedirect();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
        $this->assertStringContainsString('Aktualizacja powiodła się', $this->httpClient->getResponse()->getContent());
    }

//    /**
//     * Test create user for admin.
//     */
//    public function testDeleteAdmin(): void
//    {
//        // given
//        $expectedStatusCode = 200;
//        $adminUser = $this->createAdmin([User::ROLE_USER, User::ROLE_ADMIN]);
//        $this->logIn($adminUser);
//        $user = $this->createUser([User::ROLE_USER]);
//
//        // when
//        $crawler = $this->httpClient->request('GET', '/user/'.$user->getId().'/delete');
//        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();
//        $form = $crawler->selectButton('usuń')->form();
//        $this->httpClient->submit($form);
//        $this->httpClient->followRedirect();
//
//        // then
//        $this->assertEquals($expectedStatusCode, $resultStatusCode);
//        $this->assertStringContainsString('Usuwanie powiodło się', $this->httpClient->getResponse()->getContent());
//    }

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
     * Create user.
     *
     * @param array $roles User roles
     *
     * @return User User entity
     */
    private function createAdmin(array $roles): User
    {
        $passwordEncoder = self::$container->get('security.password_encoder');
        $user = new User();
        $user->setEmail('admin@example.com');
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
}
