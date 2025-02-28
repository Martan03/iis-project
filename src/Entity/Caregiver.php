<?php

namespace App\Entity;

use App\Repository\CaregiverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaregiverRepository::class)]
class Caregiver
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Request>
     */
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'caregiver')]
    private Collection $requests;

    #[ORM\OneToOne(mappedBy: 'caregiver', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->requests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $request->setCaregiver($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getCaregiver() === $this) {
                $request->setCaregiver(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setCaregiver(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getCaregiver() !== $this) {
            $user->setCaregiver($this);
        }

        $this->user = $user;

        return $this;
    }
}
