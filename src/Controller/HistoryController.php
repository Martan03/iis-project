<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RegistrationRepository;
use App\Repository\WalkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HistoryController extends AbstractController
{
    #[Route('/history', name: 'history')]
    #[IsGranted('ROLE_VOL')]
    public function index(RegistrationRepository $rr): Response
    {
        /**  @var User */
        $user = $this->getUser();
        $reg_list = $rr->getHistory($user->getVolunteer());
        

        return $this->render('history/history.html.twig', [
            'history_list' => $reg_list,
        ]);
    }
}
