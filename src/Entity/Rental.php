<?php
/**
 * Rental entity.
 */

namespace App\Entity;

use App\Repository\RentalRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RentalRepository::class)
 * @ORM\Table(name="rentals")
 */
class Rental
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type(type="\DateTimeInterface")
     */
    private $date_of_rental;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type(type="\DateTimeInterface")
     */
    private $date_of_return;

//    /**
//     * @ORM\ManyToOne(targetEntity=Book::class)
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $book;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="rentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @ORM\OneToOne(targetEntity=Giveback::class, mappedBy="rental", cascade={"persist", "remove"})
     */
    private $giveback;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOfRental(): ?\DateTimeInterface
    {
        return $this->date_of_rental;
    }

    /**
     * @return $this
     */
    public function setDateOfRental(\DateTimeInterface $date_of_rental): self
    {
        $this->date_of_rental = $date_of_rental;

        return $this;
    }

    public function getDateOfReturn(): ?\DateTimeInterface
    {
        return $this->date_of_return;
    }

    /**
     * @return $this
     */
    public function setDateOfReturn(\DateTimeInterface $date_of_return): self
    {
        $this->date_of_return = $date_of_return;

        return $this;
    }

//    public function getBook(): ?Book
//    {
//        return $this->book;
//    }
//
//    public function setBook(?Book $book): self
//    {
//        $this->book = $book;
//
//        return $this;
//    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * @return $this
     */
    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getGiveback(): ?Giveback
    {
        return $this->giveback;
    }

    /**
     * @return $this
     */
    public function setGiveback(Giveback $giveback): self
    {
        // set the owning side of the relation if necessary
        if ($giveback->getRental() !== $this) {
            $giveback->setRental($this);
        }

        $this->giveback = $giveback;

        return $this;
    }
}
