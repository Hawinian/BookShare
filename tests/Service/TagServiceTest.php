<?php
/**
 * TagService tests.
 */

namespace App\Tests\Service;

use App\Entity\Tag;
use App\Repository\BookRepository;
use App\Repository\TagRepository;
use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class TagServiceTest.
 */
class TagServiceTest extends KernelTestCase
{
    /**
     * Tag service.
     *
     * @var TagService|object|null
     */
    private ?TagService $tagService;

    /**
     * Tag repository.
     *
     * @var TagRepository|object|null
     */
    private ?TagRepository $tagRepository;

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
        $this->tagRepository = $container->get(TagRepository::class);
        $this->tagService = $container->get(TagService::class);
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
        $expectedTag = new Tag();
        $expectedTag->setName('Test Tag');

        // when
        $this->tagService->save($expectedTag);
        $resultTag = $this->tagRepository->findOneById(
            $expectedTag->getId()
        );

        // then
        $this->assertEquals($expectedTag, $resultTag);
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
        $expectedTag = new Tag();
        $expectedTag->setName('Test Tag');
        $this->tagRepository->save($expectedTag);
        $expectedId = $expectedTag->getId();

        // when
        $this->tagService->delete($expectedTag);
        $result = $this->tagRepository->findOneById($expectedId);

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
        $expectedTag = new Tag();
        $expectedTag->setName('Test Tag');
        $this->tagRepository->save($expectedTag);

        // when
        $result = $this->tagService->findOneById($expectedTag->getId());

        // then
        $this->assertEquals($expectedTag->getId(), $result->getId());
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
        $result = $this->tagService->createPaginatedList($page);

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
            $tag = new Tag();
            $tag->setName('Test Tag #'.$counter);
            $this->tagService->save($tag);

            ++$counter;
        }

        // when
        $result = $this->tagService->createPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, $result->count());
    }
}
