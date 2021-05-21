<?php
/**
 * PetitionKind fixture.
 */

namespace App\DataFixtures;

use App\Entity\PetitionKind;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PetitionKindFixtures.
 */
class PetitionKindFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(3, 'petition_kinds', function ($i) {
            $petiton_kind = new PetitionKind();
            $petiton_kind->setName($this->faker->word());

            return $petiton_kind;
        });

        $manager->flush();
    }
}
