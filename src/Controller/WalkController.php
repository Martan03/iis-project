<?php

namespace App\Controller;

use App\Entity\Walk;
use App\Form\WalkType;
use App\Repository\WalkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WalkController extends AbstractController
{
    private WalkRepository $wr;

    public function __construct(WalkRepository $wr)
    {
        $this->wr = $wr;
    }

    #[Route('/admin/walks', name: 'walks')]
    public function walks(Request $request): Response
    {
        $walks = $this->wr->findAll();

        return $this->render('walk/walks.html.twig', [
            'walks' => $walks,
        ]);
    }

    #[Route('/walk/{id}', name: 'walk')]
    public function walk(int $id): Response
    {
        $walk = $this->wr->findOneBy(['id' => $id]);
        if (!$walk) {
            throw $this->createNotFoundException();
        }

        return $this->render('walk/index.html.twig', [
            'walk' => $walk
        ]);
    }

    #[Route('/admin/walk/{id?}', name: 'walk_editor')]
    public function editor(Request $request, int|null $id): Response
    {
        $walk = new Walk();
        if ($id) {
            $walk = $this->wr->findOneBy(['id' => $id]);
        }

        if (!$walk) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(WalkType::class, $walk);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->wr->save($walk);
            return $this->redirectToRoute('walks');
        }

        return $this->render('walk/editor.html.twig', [
            'form' => $form,
        ]);
    }
}
