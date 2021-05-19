<?php
/**
 * Cover fixture.
 */

namespace App\DataFixtures;

use App\Entity\Cover;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CoverFixtures.
 */
class CoverFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(3, 'covers', function ($i) {
            $cover = new Cover();
            $cover->setName($this->faker->word());

            return $cover;
        });

        $manager->flush();
    }
}
