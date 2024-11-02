<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fulfilled = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToOne(inversedBy: 'request', cascade: ['persist', 'remove'])]
    private ?Examination $examination = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Caregiver $caregiver = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Veterinary $veterinary = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): static
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateFulfilled(): ?\DateTimeInterface
    {
        return $this->date_fulfilled;
    }

    public function setDateFulfilled(?\DateTimeInterface $date_fulfilled): static
    {
        $this->date_fulfilled = $date_fulfilled;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getExamination(): ?Examination
    {
        return $this->examination;
    }

    public function setExamination(?Examination $examination): static
    {
        $this->examination = $examination;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getCaregiver(): ?Caregiver
    {
        return $this->caregiver;
    }

    public function setCaregiver(?Caregiver $caregiver): static
    {
        $this->caregiver = $caregiver;

        return $this;
    }

    public function getVeterinary(): ?Veterinary
    {
        return $this->veterinary;
    }

    public function setVeterinary(?Veterinary $veterinary): static
    {
        $this->veterinary = $veterinary;

        return $this;
    }
}
