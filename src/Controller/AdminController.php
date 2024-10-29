<?php

namespace App\Controller;

use App\Repository\VolunteerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(VolunteerRepository $vr): Response
    {
        $users = $vr->findBy(['verified' => false]);

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }
}
