<?php
/**
 * PublisherService tests.
 */

namespace App\Tests\Service;

use App\Entity\Publisher;
use App\Repository\BookRepository;
use App\Repository\PublisherRepository;
use App\Service\PublisherService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class PublisherServiceTest.
 */
class PublisherServiceTest extends KernelTestCase
{
    /**
     * Publisher service.
     *
     * @var PublisherService|object|null
     */
    private ?PublisherService $publisherService;

    /**
     * Publisher repository.
     *
     * @var PublisherRepository|object|null
     */
    private ?PublisherRepository $publisherRepository;

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
        $this->publisherRepository = $container->get(PublisherRepository::class);
        $this->publisherService = $container->get(PublisherService::class);
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
        $expectedPublisher = new Publisher();
        $expectedPublisher->setName('Test Publisher');

        // when
        $this->publisherService->save($expectedPublisher);
        $resultPublisher = $this->publisherRepository->findOneById(
            $expectedPublisher->getId()
        );

        // then
        $this->assertEquals($expectedPublisher, $resultPublisher);
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
        $expectedPublisher = new Publisher();
        $expectedPublisher->setName('Test Publisher');
        $this->publisherRepository->save($expectedPublisher);
        $expectedId = $expectedPublisher->getId();

        // when
        $this->publisherService->delete($expectedPublisher);
        $result = $this->publisherRepository->findOneById($expectedId);

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
        $expectedPublisher = new Publisher();
        $expectedPublisher->setName('Test Publisher');
        $this->publisherRepository->save($expectedPublisher);

        // when
        $result = $this->publisherService->findOneById($expectedPublisher->getId());

        // then
        $this->assertEquals($expectedPublisher->getId(), $result->getId());
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
        $result = $this->publisherService->createPaginatedList($page);

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
            $publisher = new Publisher();
            $publisher->setName('Test Publisher #'.$counter);
            $this->publisherService->save($publisher);

            ++$counter;
        }

        // when
        $result = $this->publisherService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    // other tests for paginated list
}
