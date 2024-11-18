<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AnimalRepository $ar): Response
    {
        $animals = $ar->findAll();
        $animal_cnt = count($animals);
        $species_cnt = $ar->countSpecies();

        return $this->render('home/index.html.twig', [
            'animals' => $animals,
            'animal_cnt' => $animal_cnt,
            'species_cnt' => $species_cnt,
        ]);
    }
}
