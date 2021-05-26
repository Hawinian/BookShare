<?php
/**
 * PetitionKindService tests.
 */

namespace App\Tests\Service;

use App\Entity\PetitionKind;
use App\Repository\BookRepository;
use App\Repository\PetitionKindRepository;
use App\Service\PetitionKindService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class PetitionKindServiceTest.
 */
class PetitionKindServiceTest extends KernelTestCase
{
    /**
     * PetitionKind service.
     *
     * @var PetitionKindService|object|null
     */
    private ?PetitionKindService $petition_kindService;

    /**
     * PetitionKind repository.
     *
     * @var PetitionKindRepository|object|null
     */
    private ?PetitionKindRepository $petition_kindRepository;

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
        $this->petition_kindRepository = $container->get(PetitionKindRepository::class);
        $this->petition_kindService = $container->get(PetitionKindService::class);
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
        $expectedPetitionKind = new PetitionKind();
        $expectedPetitionKind->setName('Test PetitionKind');

        // when
        $this->petition_kindService->save($expectedPetitionKind);
        $resultPetitionKind = $this->petition_kindRepository->findOneById(
            $expectedPetitionKind->getId()
        );

        // then
        $this->assertEquals($expectedPetitionKind, $resultPetitionKind);
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
        $expectedPetitionKind = new PetitionKind();
        $expectedPetitionKind->setName('Test PetitionKind');
        $this->petition_kindRepository->save($expectedPetitionKind);
        $expectedId = $expectedPetitionKind->getId();

        // when
        $this->petition_kindService->delete($expectedPetitionKind);
        $result = $this->petition_kindRepository->findOneById($expectedId);

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
        $expectedPetitionKind = new PetitionKind();
        $expectedPetitionKind->setName('Test PetitionKind');
        $this->petition_kindRepository->save($expectedPetitionKind);

        // when
        $result = $this->petition_kindService->findOneById($expectedPetitionKind->getId());

        // then
        $this->assertEquals($expectedPetitionKind->getId(), $result->getId());
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
        $result = $this->petition_kindService->createPaginatedList($page);

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
            $petition_kind = new PetitionKind();
            $petition_kind->setName('Test PetitionKind #'.$counter);
            $this->petition_kindService->save($petition_kind);

            ++$counter;
        }

        // when
        $result = $this->petition_kindService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    // other tests for paginated list
}
