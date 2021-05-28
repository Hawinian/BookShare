<?php
/**
 * PetitionService tests.
 */

namespace App\Tests\Service;

use App\Entity\Petition;
use App\Repository\BookRepository;
use App\Repository\PetitionRepository;
use App\Service\PetitionService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class PetitionServiceTest.
 */
class PetitionServiceTest extends KernelTestCase
{
    /**
     * Petition service.
     *
     * @var PetitionService|object|null
     */
    private ?PetitionService $petitionService;

    /**
     * Petition repository.
     *
     * @var PetitionRepository|object|null
     */
    private ?PetitionRepository $petitionRepository;

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
        $this->petitionRepository = $container->get(PetitionRepository::class);
        $this->petitionService = $container->get(PetitionService::class);
        $this->bookRepository = $container->get(BookRepository::class);
    }


}
