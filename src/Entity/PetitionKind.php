<?php
/**
 * PetitionKind entity.
 */

namespace App\Entity;

use App\Repository\PetitionKindRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PetitionKindRepository::class)
 * @ORM\Table(name="petition_kinds")
 */
class PetitionKind
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
     * @ORM\OneToMany(targetEntity=Petition::class, mappedBy="petition_kind")
     */
    private $petitions;

    /**
     * PetitionKind constructor.
     */
    public function __construct()
    {
        $this->petition_kinds = new ArrayCollection();
        $this->petitions = new ArrayCollection();
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

    /**
     * @return Collection|Petition[]
     */
    public function getPetitions(): Collection
    {
        return $this->petitions;
    }
}
