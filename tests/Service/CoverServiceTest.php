<?php
/**
 * CoverService tests.
 */

namespace App\Tests\Service;

use App\Entity\Cover;
use App\Repository\BookRepository;
use App\Repository\CoverRepository;
use App\Service\CoverService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CoverServiceTest.
 */
class CoverServiceTest extends KernelTestCase
{
    /**
     * Cover service.
     *
     * @var CoverService|object|null
     */
    private ?CoverService $coverService;

    /**
     * Cover repository.
     *
     * @var CoverRepository|object|null
     */
    private ?CoverRepository $coverRepository;

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
        $this->coverRepository = $container->get(CoverRepository::class);
        $this->coverService = $container->get(CoverService::class);
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
        $expectedCover = new Cover();
        $expectedCover->setName('Test Cover');

        // when
        $this->coverService->save($expectedCover);
        $resultCover = $this->coverRepository->findOneById(
            $expectedCover->getId()
        );

        // then
        $this->assertEquals($expectedCover, $resultCover);
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
        $expectedCover = new Cover();
        $expectedCover->setName('Test Cover');
        $this->coverRepository->save($expectedCover);
        $expectedId = $expectedCover->getId();

        // when
        $this->coverService->delete($expectedCover);
        $result = $this->coverRepository->findOneById($expectedId);

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
        $expectedCover = new Cover();
        $expectedCover->setName('Test Cover');
        $this->coverRepository->save($expectedCover);

        // when
        $result = $this->coverService->findOneById($expectedCover->getId());

        // then
        $this->assertEquals($expectedCover->getId(), $result->getId());
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
        $result = $this->coverService->createPaginatedList($page);

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
            $cover = new Cover();
            $cover->setName('Test Cover #'.$counter);
            $this->coverService->save($cover);

            ++$counter;
        }

        // when
        $result = $this->coverService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    // other tests for paginated list
}
