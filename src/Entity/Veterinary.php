<?php

namespace App\Entity;

use App\Repository\VeterinaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VeterinaryRepository::class)]
class Veterinary extends User
{
    /**
     * @var Collection<int, Examination>
     */
    #[ORM\OneToMany(targetEntity: Examination::class, mappedBy: 'veterinary')]
    private Collection $examinations;

    /**
     * @var Collection<int, Request>
     */
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'veterinary')]
    private Collection $requests;

    public function __construct()
    {
        $this->examinations = new ArrayCollection();
        $this->requests = new ArrayCollection();
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
            $examination->setVeterinary($this);
        }

        return $this;
    }

    public function removeExamination(Examination $examination): static
    {
        if ($this->examinations->removeElement($examination)) {
            // set the owning side to null (unless already changed)
            if ($examination->getVeterinary() === $this) {
                $examination->setVeterinary(null);
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
            $request->setVeterinary($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getVeterinary() === $this) {
                $request->setVeterinary(null);
            }
        }

        return $this;
    }
}
