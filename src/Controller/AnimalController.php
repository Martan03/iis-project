<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
    public function animals(Request $request): Response
    {
        $query = $request->query->get('query', '');
        $animals = $this->ar->findAllSearch($query);

        return $this->render('animal/animals.html.twig', [
            'animals' => $animals,
        ]);
    }

    #[Route('/animal/{id}', name: 'animal')]
    public function animal(int $id): Response
    {
        $animal = $this->ar->findOneBy(['id' => $id]);
        if (!$animal)
            throw $this->createNotFoundException();


        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('animal_del', [
                'id' => $animal->getId()
            ]))
            ->setMethod('DELETE')
            ->getForm();

        return $this->render('animal/index.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    #[Route(
        '/admin/animal/{id?}',
        name: 'animal_editor',
        methods: ['GET', 'POST']
    )]
    public function editor(Request $request, int|null $id): Response
    {
        $animal = new Animal();
        if ($id) {
            $animal = $this->ar->findOneBy(['id' => $id]);
        }

        if (!$animal)
            throw $this->createNotFoundException();

        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $animal->getFile();
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();
                $file->move($this->getParameter('pics_dir'), $filename);
                $animal->setImage('/pics/' . $filename);
            }

            $id = $this->ar->save($animal);
            return $this->redirectToRoute('animal', ['id' => $id]);
        }

        return $this->render('animal/editor.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/animal/del/{id}', name: 'animal_del', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $animal = $this->ar->findOneBy(['id' => $id]);
        $this->ar->delete($animal);
        return $this->redirectToRoute('animals');
    }
}
