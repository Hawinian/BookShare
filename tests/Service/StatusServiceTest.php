<?php
/**
 * StatusService tests.
 */

namespace App\Tests\Service;

use App\Entity\Status;
use App\Repository\BookRepository;
use App\Repository\StatusRepository;
use App\Service\StatusService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class StatusServiceTest.
 */
class StatusServiceTest extends KernelTestCase
{
    /**
     * Status service.
     *
     * @var StatusService|object|null
     */
    private ?StatusService $statusService;

    /**
     * Status repository.
     *
     * @var StatusRepository|object|null
     */
    private ?StatusRepository $statusRepository;

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
        $this->statusRepository = $container->get(StatusRepository::class);
        $this->statusService = $container->get(StatusService::class);
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
        $expectedStatus = new Status();
        $expectedStatus->setName('Test Status');

        // when
        $this->statusService->save($expectedStatus);
        $resultStatus = $this->statusRepository->findOneById(
            $expectedStatus->getId()
        );

        // then
        $this->assertEquals($expectedStatus, $resultStatus);
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
        $expectedStatus = new Status();
        $expectedStatus->setName('Test Status');
        $this->statusRepository->save($expectedStatus);
        $expectedId = $expectedStatus->getId();

        // when
        $this->statusService->delete($expectedStatus);
        $result = $this->statusRepository->findOneById($expectedId);

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
        $expectedStatus = new Status();
        $expectedStatus->setName('Test Status');
        $this->statusRepository->save($expectedStatus);

        // when
        $result = $this->statusService->findOneById($expectedStatus->getId());

        // then
        $this->assertEquals($expectedStatus->getId(), $result->getId());
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
        $result = $this->statusService->createPaginatedList($page);

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
            $status = new Status();
            $status->setName('Test Status #'.$counter);
            $this->statusService->save($status);

            ++$counter;
        }

        // when
        $result = $this->statusService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }
}
