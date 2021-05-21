<?php
/**
 * Publisher fixture.
 */

namespace App\DataFixtures;

use App\Entity\Publisher;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PublisherFixtures.
 */
class PublisherFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'publishers', function ($i) {
            $publisher = new Publisher();
            $publisher->setName($this->faker->word());

            return $publisher;
        });

        $manager->flush();
    }
}
