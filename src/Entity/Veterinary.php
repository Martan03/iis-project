<?php

namespace App\Entity;

use App\Repository\VeterinaryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VeterinaryRepository::class)]
class Veterinary extends User
{
}
