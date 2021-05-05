<?php
/**
 * Language fixture.
 */

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Persistence\ObjectManager;

/**
 * Class LanguageFixtures.
 */
class LanguageFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'languages', function ($i) {
            $language = new Language();
            $language->setName($this->faker->country);

            return $language;
        });

        $manager->flush();
    }
}
