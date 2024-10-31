<?php

namespace App\Entity;

use App\Repository\CaregiverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaregiverRepository::class)]
class Caregiver extends User
{
    /**
     * @var Collection<int, Request>
     */
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'caregiver')]
    private Collection $requests;

    public function __construct()
    {
        $this->requests = new ArrayCollection();
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
}
