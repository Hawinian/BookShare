<?php
/**
 * Giveback fixture.
 */

namespace App\DataFixtures;

use App\Entity\Giveback;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class GivebackFixtures.
 */
class GivebackFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(1, 'givebacks', function ($i) {
            $giveback = new Giveback();
            $giveback->setRental($this->getRandomReference('rentals'));
            $giveback->setDate($this->faker->dateTime($max = 'now'));
            $giveback->setContent($this->faker->text($maxNbChars = 300));

            return $giveback;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [RentalFixtures::class];
    }
}
