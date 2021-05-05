<?php
/**
 * Status fixture.
 */

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Persistence\ObjectManager;

/**
 * Class StatusFixtures.
 */
class StatusFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(3, 'status', function ($i) {
            $status = new Status();
            $status->setName($this->faker->word);

            return $status;
        });

        $manager->flush();
    }
}
