<?php
/**
 * Book entity.
 */

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ORM\Table(name="books")
 */
class Book
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
     *
     * @Assert\Length(
     *     allowEmptyString="false",
     *     min="2",
     *     max="64",
     *     )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Type(type="integer")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Type(type="integer")
     */
    private $pages;

    /**
     * @ORM\Column(type="text")
     *
     * Assert\Type(type="string")
     * @Assert\Length(
     *     allowEmptyString=false,
     *     min="2",
     *     max="500"
     *     )
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Cover::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cover;

    /**
     * @ORM\ManyToOne(targetEntity=Publisher::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publisher;

    /**
     * @ORM\ManyToOne(targetEntity=Language::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

//    /**
//     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="books")
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="books")
     */
    private $tag;

    /**
     * @ORM\OneToMany(targetEntity=Vote::class, mappedBy="book", orphanRemoval=true)
     */
    private $votes;

    /**
     * @ORM\OneToMany(targetEntity=Rental::class, mappedBy="book", orphanRemoval=true)
     */
    private $rentals;

    /**
     * @ORM\OneToMany(targetEntity=Petition::class, mappedBy="book", orphanRemoval=true)
     */
    private $petitions;

    /**
     * Book constructor.
     */
    public function __construct()
    {
        $this->tag = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->rentals = new ArrayCollection();
        $this->petitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDate(): ?int
    {
        return $this->date;
    }

    /**
     * @return $this
     */
    public function setDate(int $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    /**
     * @return $this
     */
    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @return $this
     */
    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * @return $this
     */
    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCover(): ?Cover
    {
        return $this->cover;
    }

    /**
     * @return $this
     */
    public function setCover(?Cover $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    /**
     * @return $this
     */
    public function setPublisher(?Publisher $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    /**
     * @return $this
     */
    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * @return $this
     */
    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

//    public function getCategory(): ?Category
//    {
//        return $this->category;
//    }
//
//    public function setCategory(?Category $category): self
//    {
//        $this->category = $category;
//
//        return $this;
//    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    /**
     * @return $this
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection|Vote[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    /**
     * @return $this
     */
    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setBook($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getBook() === $this) {
                $vote->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rental[]
     */
    public function getRentals(): Collection
    {
        return $this->rentals;
    }

    /**
     * @return $this
     */
    public function addRental(Rental $rental): self
    {
        if (!$this->rentals->contains($rental)) {
            $this->rentals[] = $rental;
            $rental->setBook($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeRental(Rental $rental): self
    {
        if ($this->rentals->removeElement($rental)) {
            // set the owning side to null (unless already changed)
            if ($rental->getBook() === $this) {
                $rental->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Petition[]
     */
    public function getPetitions(): Collection
    {
        return $this->petitions;
    }

    public function addPetition(Petition $petition): self
    {
        if (!$this->petitions->contains($petition)) {
            $this->petitions[] = $petition;
            $petition->setBook($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removePetition(Petition $petition): self
    {
        if ($this->petitions->removeElement($petition)) {
            // set the owning side to null (unless already changed)
            if ($petition->getBook() === $this) {
                $petition->setBook(null);
            }
        }

        return $this;
    }
}
