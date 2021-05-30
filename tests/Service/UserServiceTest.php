<?php
/**
 * UserService tests.
 */

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserServiceTest.
 */
class UserServiceTest extends KernelTestCase
{
    /**
     * User service.
     *
     * @var UserService|object|null
     */
    private ?UserService $userService;

    /**
     * User repository.
     *
     * @var UserRepository|object|null
     */
    private ?UserRepository $userRepository;

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
        $this->userRepository = $container->get(UserRepository::class);
        $this->userService = $container->get(UserService::class);
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
        $passwordEncoder = self::$container->get('security.password_encoder');
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setRoles([User::ROLE_USER]);
        $user->setLogin('user');
        $user->setStatus(100);
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                'p@55w0rd'
            )
        );

        // when
        $this->userService->save($user);
        $resultUser = $this->userRepository->findOneById(
            $user->getId()
        );

        // then
        $this->assertEquals($user, $resultUser);
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
        $expectedUser = new User();
        $expectedUser = $this->createUser([User::ROLE_USER], 'user1@example.com');
        $expectedId = $expectedUser->getId();

        // when
        $this->userService->delete($expectedUser);
        $result = $this->userRepository->findOneById($expectedId);

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
        $expectedUser = new User();
        $expectedUser = $this->createUser([User::ROLE_USER], 'user1@example.com');

        // when
        $result = $this->userService->findOneById($expectedUser->getId());

        // then
        $this->assertEquals($expectedUser->getId(), $result->getId());
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
        $result = $this->userService->createPaginatedList($page);

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
            $passwordEncoder = self::$container->get('security.password_encoder');
            $user = new User();
            $user->setEmail('user'.$counter.'@email.com');
            $user->setRoles([User::ROLE_USER]);
            $user->setLogin('user');
            $user->setStatus(100);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    'p@55w0rd'
                )
            );
            $this->userService->save($user);

            ++$counter;
        }

        // when
        $result = $this->userService->createPaginatedList($page);

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
}
