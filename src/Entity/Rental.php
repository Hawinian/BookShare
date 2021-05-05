<?php

namespace App\Entity;

use App\Repository\RentalRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $date_of_rental;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_of_return;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOfRental(): ?\DateTimeInterface
    {
        return $this->date_of_rental;
    }

    public function setDateOfRental(\DateTimeInterface $date_of_rental): self
    {
        $this->date_of_rental = $date_of_rental;

        return $this;
    }

    public function getDateOfReturn(): ?\DateTimeInterface
    {
        return $this->date_of_return;
    }

    public function setDateOfReturn(\DateTimeInterface $date_of_return): self
    {
        $this->date_of_return = $date_of_return;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
