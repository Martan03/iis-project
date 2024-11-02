<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\VolunteerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    private UserRepository $ur;

    public function __construct(UserRepository $ur)
    {
        $this->ur = $ur;
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function users(Request $request): Response
    {
        $query = $request->query->get('query', '');
        $users = $this->ur->findAllSearch($query);

        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'search_in' => '/admin/users',
        ]);
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/verify', name: 'admin_verify')]
    public function verify(Request $request, VolunteerRepository $vr): Response
    {
        $filter = $request->query->get('filter', 'to-verify');
        $filterVal = match ($filter) {
            'to-verify' => false,
            'verified' => true,
            'denied' => null,
            default => false,
        };
        $users = $vr->findBy(['verified' => $filterVal]);

        return $this->render('admin/caregiver/verify.html.twig', [
            'users' => $users,
            'filterVal' => $filterVal,
        ]);
    }

    #[Route('/admin/verify/{id}', name: 'admin_verify_detail')]
    public function verify_detail(
        Request $request,
        VolunteerRepository $vr,
        int $id
    ): Response
    {
        $user = $vr->findOneBy(['id' => $id]);
        if (!$user) {
            return $this->redirectToRoute('admin_verify');
        }

        $verifyForm = $this->createFormBuilder()
            ->add('verify', SubmitType::class)
            ->getForm();
        $verifyForm->handleRequest($request);
        if ($verifyForm->isSubmitted() && $verifyForm->isValid()) {
            $user->setVerified(true);
            $vr->save($user);
            return $this->redirectToRoute('admin_verify');
        }

        $toVerifyForm = $this->createFormBuilder()
            ->add('to_verify', SubmitType::class)
            ->getForm();
        $toVerifyForm->handleRequest($request);
        if ($toVerifyForm->isSubmitted() && $toVerifyForm->isValid()) {
            $user->setVerified(false);
            $vr->save($user);
            return $this->redirectToRoute('admin_verify');
        }

        $denyForm = $this->createFormBuilder()
            ->add('deny', SubmitType::class)
            ->getForm();
        $denyForm->handleRequest($request);
        if ($denyForm->isSubmitted() && $denyForm->isValid()) {
            $user->setVerified(null);
            $vr->save($user);
            return $this->redirectToRoute('admin_verify');
        }

        return $this->render('admin/caregiver/verify_detail.html.twig', [
            'user' => $user,
            'verifyForm' => $verifyForm,
            'toVerifyForm' => $toVerifyForm,
            'denyForm' => $denyForm,
        ]);
    }
}
