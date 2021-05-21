<?php
/**
 * AccountStatus fixture.
 */

namespace App\DataFixtures;

use App\Entity\AccountStatus;
use Doctrine\Persistence\ObjectManager;

/**
 * Class StatusFixtures.
 */
class AccountStatusFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(3, 'account_status', function ($i) {
            $account_status = new AccountStatus();
            $account_status->setName($this->faker->word());

            return $account_status;
        });

        $manager->flush();
    }
}
