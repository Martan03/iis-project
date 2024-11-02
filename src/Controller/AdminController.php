<?php

namespace App\Controller;

use App\Repository\AdministratorRepository;
use App\Repository\CaregiverRepository;
use App\Repository\UserRepository;
use App\Repository\VeterinaryRepository;
use App\Repository\VolunteerRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    private UserRepository $ur;
    private VolunteerRepository $vr;

    public function __construct(
        UserRepository $ur,
        VolunteerRepository $vr,
    )
    {
        $this->ur = $ur;
        $this->vr = $vr;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
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

    #[Route('/admin/user/{id}', name: 'admin_user')]
    public function user(int $id, Request $request): Response
    {
        switch ($request->query->get('act', '')) {
            case '':
                break;
            case 'r_Admin':
                // TODO: remove admin
                break;
            case 'a_Admin':
                // TODO: add admin
                break;
            case 'r_Caregiver':
                // TODO: remove cargiver
                break;
            case 'a_Caregiver':
                // TODO: add caregiver
                break;
            case 'r_Veterinary':
                // TODO: remove veterinary
                break;
            case 'a_Veterinary':
                // TODO: add veterinary
                break;
            case 'r_Volunteer':
                // TODO: remove volunteer
                break;
            case 'a_Volunteer':
                // TODO: add volunteer
                break;
            case 'delete':
                // TODO: delete user
                return new RedirectResponse($this->generateUrl('admin_users'));
            default:
                throw new Exception("Invalid action on user.");
        }

        $user = $this->ur->findOneBy(['id' => $id]);

        $is_admin = !is_null($user->getAdministrator());
        $is_caregiver = !is_null($user->getCaregiver());
        $is_veterinary = !is_null($user->getVeterinary());
        $is_volunteer = !is_null($user->getVolunteer());

        return $this->render('admin/user.html.twig', [
            'user' => $user,
            'search_in' => '/admin/users',
            'roles' => [
                [ 'name' => 'Admin', 'is' => $is_admin ],
                [ 'name' => 'Caregiver', 'is' => $is_caregiver ],
                [ 'name' => 'Veterinary', 'is' => $is_veterinary ],
                [ 'name' => 'Volunteer', 'is' => $is_volunteer ],
            ],
        ]);
    }

    #[Route('/admin/verify', name: 'admin_verify')]
    public function verify(Request $request): Response
    {
        $filter = $request->query->get('filter', 'to-verify');
        $filterVal = match ($filter) {
            'to-verify' => false,
            'verified' => true,
            'denied' => null,
            default => false,
        };
        $users = $this->vr->findBy(['verified' => $filterVal]);

        return $this->render('admin/caregiver/verify.html.twig', [
            'vols' => $users,
            'filterVal' => $filterVal,
        ]);
    }

    #[Route('/admin/verify/{id}', name: 'admin_verify_detail')]
    public function verify_detail(Request $request, int $id): Response
    {
        $user = $this->ur->findOneBy(['id' => $id]);
        if (!$user) {
            return $this->redirectToRoute('admin_verify');
        }

        $verifyForm = $this->createFormBuilder()
            ->add('verify', SubmitType::class)
            ->getForm();
        $verifyForm->handleRequest($request);
        if ($verifyForm->isSubmitted() && $verifyForm->isValid()) {
            $user->setVerified(true);
            $this->ur->save($user);
            return $this->redirectToRoute('admin_verify');
        }

        $toVerifyForm = $this->createFormBuilder()
            ->add('to_verify', SubmitType::class)
            ->getForm();
        $toVerifyForm->handleRequest($request);
        if ($toVerifyForm->isSubmitted() && $toVerifyForm->isValid()) {
            $user->setVerified(false);
            $this->ur->save($user);
            return $this->redirectToRoute('admin_verify');
        }

        $denyForm = $this->createFormBuilder()
            ->add('deny', SubmitType::class)
            ->getForm();
        $denyForm->handleRequest($request);
        if ($denyForm->isSubmitted() && $denyForm->isValid()) {
            $user->setVerified(null);
            $this->ur->save($user);
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
