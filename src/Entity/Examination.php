<?php

namespace App\Entity;

use App\Repository\ExaminationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExaminationRepository::class)]
class Examination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $result = null;

    #[ORM\ManyToOne(inversedBy: 'examinations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    #[ORM\OneToOne(mappedBy: 'examination', cascade: ['persist', 'remove'])]
    private ?Request $request = null;

    #[ORM\ManyToOne(inversedBy: 'examinations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Veterinary $veterinary = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): static
    {
        $this->result = $result;

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

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): static
    {
        // unset the owning side of the relation if necessary
        if ($request === null && $this->request !== null) {
            $this->request->setExamination(null);
        }

        // set the owning side of the relation if necessary
        if ($request !== null && $request->getExamination() !== $this) {
            $request->setExamination($this);
        }

        $this->request = $request;

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
