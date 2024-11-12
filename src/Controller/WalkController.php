<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Entity\Walk;
use App\Entity\User;
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

            // TODO: don't allow registering more times for the same walk by
            // the same user
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
        $walks = $this->wr->getFuture();

        return $this->render('walk/registrations.html.twig', [
            'walks' => $walks,
        ]);
    }

    #[Route('/admin/registration/{id}', name: 'registration')]
    #[IsGranted('ROLE_CARER')]
    public function registration(int $id, Request $req): Response
    {
        /** @var Walk */
        $walk = $this->wr->findOneBy(['id' => $id]);
        if (!$walk) {
            throw $this->createNotFoundException();
        }

        $to_select = $req->request->get('select', null);
        if (!is_null($to_select)) {
            /** @var Registration */
            $sel = $this->rr->findOneBy(
                ['walk' => $walk, 'state' => 'selected']
            );
            if (!is_null($sel)) {
                $sel->setState('waiting');
                $this->rr->save($sel);
            }

            /** @var Registration */
            $reg = $this->rr->findOneBy(['id' => $to_select]);
            $reg->setState('selected');
            $this->rr->save($reg);
        }

        $animal = $walk->getAnimal();
        /** @var array[Registration] */
        $adepts = $this->rr->findBy(['walk' => $walk]);
        /** @var Registration */
        $user = $this->rr->findOneBy(['walk' => $walk, 'state' => 'selected']);

        return $this->render('walk/registration.html.twig', [
            'walk' => $walk,
            'start' => $walk->getStart()->format("D d.m Y H:i"),
            'end' => $walk->getEnd()->format("H:i"),
            'animal' => $animal,
            'adepts' => $adepts,
            'user' => $user,
        ]);
    }
}
