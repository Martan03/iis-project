<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimalController extends AbstractController
{
    private AnimalRepository $ar;

    public function __construct(AnimalRepository $ar)
    {
        $this->ar = $ar;
    }

    #[Route('/animals', name: 'animals')]
    public function animals(): Response
    {
        $animals = $this->ar->findAll();

        return $this->render('animal/animals.html.twig', [
            'animals' => $animals,
        ]);
    }

    #[Route('/animal/{id}', name: 'animal')]
    public function animal(int $id): Response
    {
        $animal = $this->ar->findOneBy(['id' => $id]);
        if (!$animal)
            return $this->createNotFoundException();

        return $this->render('animal/index.html.twig', [
            'animal' => $animal,
        ]);
    }

    #[Route('/admin/animal/{id?}', name: 'animal_editor')]
    public function editor(Request $request, int|null $id): Response
    {
        $animal = new Animal();
        if ($id) {
            $animal = $this->ar->findOneBy(['id' => $id]);
        }

        if (!$animal)
            return $this->createNotFoundException();

        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $animal->getFile();
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('pics_dir'), $filename);
                $animal->setImage('/pics/' . $filename);
            }

            $this->ar->save($animal);
        }

        return $this->render('animal/editor.html.twig', [
            'form' => $form,
        ]);
    }
}
