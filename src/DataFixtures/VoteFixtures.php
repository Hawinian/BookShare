<?php
/**
 * Vote fixture.
 */

namespace App\DataFixtures;

use App\Entity\Vote;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class VoteFixtures.
 */
class VoteFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'votes', function ($i) {
            $vote = new Vote();
            $vote->setRate($this->faker->numberBetween($min = 1, $max = 10));
            $vote->setUser($this->getRandomReference('users'));
            $vote->setBook($this->getRandomReference('books'));

            return $vote;
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
