<?php
/**
 * Rental fixture.
 */

namespace App\DataFixtures;

use App\Entity\Rental;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class RentalFixtures.
 */
class RentalFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(100, 'rentals', function ($i) {
            $rental = new Rental();
            $rental->setDateOfRental($this->faker->dateTime($max = 'now'));
            $rental->setDateOfReturn($this->faker->dateTime($max = 'now'));
            $rental->setUser($this->getRandomReference('users'));
            $rental->setBook($this->getRandomReference('books'));

            return $rental;
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
        return [BookFixtures::class, UserFixtures::class];
    }
}
