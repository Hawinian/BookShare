<?php
/**
 * Book fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class BookFixtures.
 */
class BookFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(100, 'books', function ($i) {
            $book = new Book();
            $book->setTitle($this->faker->sentence($nbWords = 4, $variableNbWords = true));
            $book->setCategory($this->getRandomReference('categories'));
            $book->setAuthor($this->getRandomReference('authors'));
            $book->setCover($this->getRandomReference('covers'));
            $book->setLanguage($this->getRandomReference('languages'));
            $book->setStatus($this->getRandomReference('status'));
            $book->setPublisher($this->getRandomReference('publishers'));
            $book->setPages($this->faker->numberBetween($min = 150, $max = 1000));
            $book->setDate($this->faker->numberBetween($min = 1940, $max = 2021));
            $book->setDescription($this->faker->text($maxNbChars = 200));
            $book->setImage($this->faker->text($maxNbChars = 200));

            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(1, 5)
            );

            foreach ($tags as $tag) {
                $book->addTag($tag);
            }

            return $book;
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
        return [CategoryFixtures::class, AuthorFixtures::class, CoverFixtures::class, LanguageFixtures::class, PublisherFixtures::class, StatusFixtures::class, TagFixtures::class];
    }
}
