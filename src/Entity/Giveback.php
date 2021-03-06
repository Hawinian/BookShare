<?php
/**
 * Giveback entity.
 */

namespace App\Entity;

use App\Repository\GivebackRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GivebackRepository::class)
 * @ORM\Table(name="givebacks")
 */
class Giveback
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

//    /**
//     * @ORM\ManyToOne(targetEntity=Rental::class)
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $rental;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     allowEmptyString=false,
     *     min="2",
     *     )
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type(type="\DateTimeInterface")
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity=Rental::class, inversedBy="giveback")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rental;

    public function getId(): ?int
    {
        return $this->id;
    }

//    public function getRental(): ?Rental
//    {
//        return $this->rental;
//    }
//
//    public function setRental(?Rental $rental): self
//    {
//        $this->rental = $rental;
//
//        return $this;
//    }

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

    public function getRental(): ?Rental
    {
        return $this->rental;
    }

    /**
     * @return $this
     */
    public function setRental(Rental $rental): self
    {
        $this->rental = $rental;

        return $this;
    }
}
