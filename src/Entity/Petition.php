<?php
/**
 * Petition entity.
 */

namespace App\Entity;

use App\Repository\PetitionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PetitionRepository::class)
 * @ORM\Table(name="petitions")
 */
class Petition
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
    private $date;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     allowEmptyString=false,
     *     min="3",
     *     )
     */
    private $content;

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
     * @ORM\ManyToOne(targetEntity=PetitionKind::class, inversedBy="petitions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $petition_kind;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="petitions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @return $this
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getPetitionKind(): ?PetitionKind
    {
        return $this->petition_kind;
    }

    /**
     * @return $this
     */
    public function setPetitionKind(?PetitionKind $petition_kind): self
    {
        $this->petition_kind = $petition_kind;

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
}
