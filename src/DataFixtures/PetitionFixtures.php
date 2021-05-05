<?php
/**
 * Petition fixture.
 */

namespace App\DataFixtures;

use App\Entity\Petition;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PetitionFixtures.
 */
class PetitionFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(1, 'petitons', function ($i) {
            $petiton = new Petition();
            $petiton->setDate($this->faker->dateTime($max = 'now'));
            $petiton->setContent($this->faker->text($maxNbChars = 300));
            $petiton->setUser($this->getRandomReference('users'));
            $petiton->setBook($this->getRandomReference('books'));
            $petiton->setPetitionKind($this->getRandomReference('petition_kinds'));

            return $petiton;
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
        return [BookFixtures::class, UserFixtures::class, PetitionKindFixtures::class];
    }
}
