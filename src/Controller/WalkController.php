<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Entity\Walk;
use App\Form\WalkType;
use App\Repository\RegistrationRepository;
use App\Repository\WalkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class WalkController extends AbstractController
{
    private WalkRepository $wr;
    private RegistrationRepository $rr;

    public function __construct(WalkRepository $wr, RegistrationRepository $rr)
    {
        $this->wr = $wr;
        $this->rr = $rr;
    }

    #[Route('/walk/register/{id}', name: 'walk_register')]
    #[IsGranted('ROLE_VOL')]
    public function register(int $id): Response
    {
        $walk = $this->wr->findOneBy(['id' => $id]);
        if (!$walk) {
            throw $this->createNotFoundException();
        }

        return $this->render('walk/register.html.twig', [
            'walk' => $walk,
        ]);
    }

    #[Route('/walk/{id}', name: 'walk')]
    public function walk(Request $request, int $id): Response
    {
        $walk = $this->wr->findOneBy(['id' => $id]);
        if (!$walk) {
            throw $this->createNotFoundException();
        }

        $form = $this->createFormBuilder()
            ->add('register', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User */
            $user = $this->getUser();

            $registration = (new Registration())
                ->setState('waiting')
                ->setVolunteer($user->getVolunteer())
                ->setWalk($walk);
            $this->rr->save($registration);
            $walk->addRegistration($registration);

            return $this->redirectToRoute('walk_register', [
                'id' => $walk->getId()]
            );
        }

        return $this->render('walk/index.html.twig', [
            'walk' => $walk,
            'form' => $form,
        ]);
    }

    #[Route('/admin/walks', name: 'walks')]
    #[IsGranted('ROLE_CARER')]
    public function walks(): Response
    {
        $walks = $this->wr->findAll();

        return $this->render('walk/walks.html.twig', [
            'walks' => $walks,
        ]);
    }

    #[Route('/admin/walk/{id?}', name: 'walk_editor')]
    #[IsGranted('ROLE_CARER')]
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

    #[Route('/admin/registrations', name: 'registrations')]
    #[IsGranted('ROLE_CARER')]
    public function registrations(): Response
    {
        $registrations = $this->rr->findAll();

        return $this->render('walk/registrations.html.twig', [
            'registrations' => $registrations,
        ]);
    }

    #[Route('/admin/registration/{id}', name: 'registration')]
    #[IsGranted('ROLE_CARER')]
    public function registration(int $id): Response
    {
        $registration = $this->rr->findOneBy(['id' => $id]);
        if (!$registration) {
            throw $this->createNotFoundException();
        }

        return $this->render('walk/registration.html.twig', [
            'registration' => $registration,
        ]);
    }
}
