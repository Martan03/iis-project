<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $species = null;

    #[ORM\Column(length: 255)]
    private ?string $breed = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birth = null;

    #[ORM\Column(length: 255)]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column]
    private ?float $height = null;

    #[ORM\Column]
    private ?float $weight = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    private ?File $file = null;

    /**
     * @var Collection<int, Examination>
     */
    #[ORM\OneToMany(targetEntity: Examination::class, mappedBy: 'animal', orphanRemoval: true)]
    private Collection $examinations;

    /**
     * @var Collection<int, Walk>
     */
    #[ORM\OneToMany(targetEntity: Walk::class, mappedBy: 'animal')]
    private Collection $walks;

    /**
     * @var Collection<int, Request>
     */
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'animal')]
    private Collection $requests;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $discovery_date = null;

    #[ORM\Column(length: 255)]
    private ?string $discovery_place = null;

    #[ORM\Column(length: 255)]
    private ?string $handicap = null;

    #[ORM\Column]
    private ?bool $castration = null;

    public function __construct()
    {
        $this->examinations = new ArrayCollection();
        $this->walks = new ArrayCollection();
        $this->requests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getBreed(): ?string
    {
        return $this->breed;
    }

    public function setBreed(string $breed): static
    {
        $this->breed = $breed;

        return $this;
    }

    public function getBirth(): ?\DateTimeInterface
    {
        return $this->birth;
    }

    public function setBirth(?\DateTimeInterface $birth): static
    {
        $this->birth = $birth;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return Collection<int, Examination>
     */
    public function getExaminations(): Collection
    {
        return $this->examinations;
    }

    public function addExamination(Examination $examination): static
    {
        if (!$this->examinations->contains($examination)) {
            $this->examinations->add($examination);
            $examination->setAnimal($this);
        }

        return $this;
    }

    public function removeExamination(Examination $examination): static
    {
        if ($this->examinations->removeElement($examination)) {
            // set the owning side to null (unless already changed)
            if ($examination->getAnimal() === $this) {
                $examination->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Walk>
     */
    public function getWalks(): Collection
    {
        return $this->walks;
    }

    public function addWalk(Walk $walk): static
    {
        if (!$this->walks->contains($walk)) {
            $this->walks->add($walk);
            $walk->setAnimal($this);
        }

        return $this;
    }

    public function removeWalk(Walk $walk): static
    {
        if ($this->walks->removeElement($walk)) {
            // set the owning side to null (unless already changed)
            if ($walk->getAnimal() === $this) {
                $walk->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setAnimal($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getAnimal() === $this) {
                $request->setAnimal(null);
            }
        }

        return $this;
    }

    public function getDiscoveryDate(): ?\DateTimeInterface
    {
        return $this->discovery_date;
    }

    public function setDiscoveryDate(\DateTimeInterface $discovery_date): static
    {
        $this->discovery_date = $discovery_date;

        return $this;
    }

    public function getDiscoveryPlace(): ?string
    {
        return $this->discovery_place;
    }

    public function setDiscoveryPlace(string $discovery_place): static
    {
        $this->discovery_place = $discovery_place;

        return $this;
    }

    public function getHandicap(): ?string
    {
        return $this->handicap;
    }

    public function setHandicap(string $handicap): static
    {
        $this->handicap = $handicap;

        return $this;
    }

    public function isCastration(): ?bool
    {
        return $this->castration;
    }

    public function setCastration(bool $castration): static
    {
        $this->castration = $castration;

        return $this;
    }
}
