<?php

namespace App\Entity;

use App\Repository\VolunteerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolunteerRepository::class)]
class Volunteer extends User
{
    #[ORM\Column]
    private ?bool $verified = null;

    /**
     * @var Collection<int, Registration>
     */
    #[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'volunteer')]
    private Collection $registrations;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * @return Collection<int, Registration>
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): static
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations->add($registration);
            $registration->setVolunteer($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): static
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getVolunteer() === $this) {
                $registration->setVolunteer(null);
            }
        }

        return $this;
    }
}
