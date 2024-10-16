<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimalController extends AbstractController
{
    #[Route('/animals', name: 'animals')]
    public function animals(AnimalRepository $ar): Response
    {
        $animals = $ar->findAll();

        return $this->render('animal/index.html.twig', [
            'animals' => $animals,
        ]);
    }
}
