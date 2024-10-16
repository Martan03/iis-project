<?php

namespace App\Entity;

use App\Repository\CaregiverRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaregiverRepository::class)]
class Caregiver extends User
{
}
