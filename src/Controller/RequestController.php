<?php

namespace App\Controller;

use App\Entity\Request as EntityRequest;
use App\Form\RequestType;
use App\Entity\User;
use App\Repository\RequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RequestController extends AbstractController
{
    #[Route('/admin/requests', name: 'requests')]
    public function requests(Request $request, RequestRepository $rr): Response
    {
        $filter = $request->query->get('filter', 'waiting');
        $requests = match ($filter) {
            'scheduled' => $rr->findAllScheduled(),
            'done' => $rr->findAllDone(),
            default => $rr->findAllWaiting(),
        };

        return $this->render('request/requests.html.twig', [
            'requests' => $requests,
            'filt' => $filter,
        ]);
    }

    #[Route('/admin/request/editor', name: 'request_editor')]
    #[IsGranted('ROLE_CARER')]
    public function editor(Request $request, RequestRepository $rr): Response
    {
        $req = new EntityRequest();

        $form = $this->createForm(RequestType::class, $req);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User */
            $user = $this->getUser();

            $req
                ->setCaregiver($user->getCaregiver())
                ->setDateCreated(new \DateTime());
            $rr->save($req);
            return $this->redirectToRoute('requests');
        }

        return $this->render('request/editor.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/request/{id}', name: 'request')]
    public function request(RequestRepository $rr, int $id): Response
    {
        $request = $rr->findOneBy(['id' => $id]);
        if (!$request) {
            return $this->redirectToRoute('requests');
        }

        return $this->render('request/index.html.twig', [
            'request' => $request,
        ]);
    }
}
