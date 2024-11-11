<?php

namespace App\Controller;

use App\Entity\Administrator;
use App\Entity\Veterinary;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\VolunteerRepository;
use Exception;
use Proxies\__CG__\App\Entity\Caregiver;
use Proxies\__CG__\App\Entity\Volunteer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted('ROLE_ADMIN')]
    public function users(Request $request): Response
    {
        $query = $request->query->get('query', '');
        $users = $this->ur->findAllSearch($query);

        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'search_in' => '/admin/users',
            'search_text' => 'Users',
        ]);
    }

    #[Route('/admin/user/{id}', name: 'admin_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function user(int $id, Request $request): Response
    {
        /** @var User */
        $user = $this->ur->findOneBy(['id' => $id]);
        $action = $request->request->get('act', '');
        $error = null;

        if ($action != '') {
            /** @var User */
            $cur_user = $this->getUser();
            if ($cur_user->getId() == $user->getId()) {
                $error = "Admin cannot edit himself.";
            }
        } else {
            switch ($action) {
                case '':
                    break;
                case 'r_Admin':
                    $user->setAdministrator(null);
                    break;
                case 'a_Admin':
                    $user->setAdministrator(new Administrator());
                    break;
                case 'r_Caregiver':
                    $user->setCaregiver(null);
                    break;
                case 'a_Caregiver':
                    $user->setCaregiver(new Caregiver());
                    break;
                case 'r_Veterinary':
                    $user->setVeterinary(null);
                    break;
                case 'a_Veterinary':
                    $user->setVeterinary(new Veterinary());
                    break;
                case 'r_Volunteer':
                    $user->setVolunteer(null);
                    break;
                case 'a_Volunteer':
                    $user->setVolunteer(new Volunteer());
                    break;
                case 'delete':
                    $this->ur->delete($user);
                    return new RedirectResponse(
                        $this->generateUrl('admin_users')
                    );
                default:
                    $error = "Invalid action on user.";
                    break;
            }

            if ($action != '') {
                $this->ur->save($user);
            }
        }

        $is_admin = !is_null($user->getAdministrator());
        $is_caregiver = !is_null($user->getCaregiver());
        $is_veterinary = !is_null($user->getVeterinary());
        $is_volunteer = !is_null($user->getVolunteer());

        return $this->render('admin/user.html.twig', [
            'user' => $user,
            'search_in' => '/admin/users',
            'search_text' => 'Users',
            'error' => $error,
            'roles' => [
                [ 'name' => 'Admin', 'is' => $is_admin ],
                [ 'name' => 'Caregiver', 'is' => $is_caregiver ],
                [ 'name' => 'Veterinary', 'is' => $is_veterinary ],
                [ 'name' => 'Volunteer', 'is' => $is_volunteer ],
            ],
        ]);
    }

    #[Route('/admin/verify', name: 'admin_verify')]
    #[IsGranted('ROLE_CARER')]
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
    #[IsGranted('ROLE_CARER')]
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
