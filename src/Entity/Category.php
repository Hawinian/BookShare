<?php
/**
 * Category entity.
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     allowEmptyString=false,
     *     min="2",
     *     max="64",
     *     )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="category")
     */
    private $books;

//    /**
//     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="category")
//     */
//    private $books;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

//    /**
//     * @return Collection|Book[]
//     */
//    public function getBooks(): Collection
//    {
//        return $this->books;
//    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

//
//    public function addBook(Book $book): self
//    {
//        if (!$this->books->contains($book)) {
//            $this->books[] = $book;
//            $book->setCategory($this);
//        }
//
//        return $this;
//    }
//
//    public function removeBook(Book $book): self
//    {
//        if ($this->books->removeElement($book)) {
//            // set the owning side to null (unless already changed)
//            if ($book->getCategory() === $this) {
//                $book->setCategory(null);
//            }
//        }
//
//        return $this;
//    }
}
