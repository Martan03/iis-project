<?php

namespace App\Controller;

use App\Entity\Examination;
use App\Entity\Request as EntityRequest;
use App\Form\RequestType;
use App\Entity\User;
use App\Form\ExaminationType;
use App\Repository\ExaminationRepository;
use App\Repository\RequestRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RequestController extends AbstractController
{
    private RequestRepository $rr;
    private ExaminationRepository $er;

    public function __construct(
        RequestRepository $rr,
        ExaminationRepository $er
    ) {
        $this->rr = $rr;
        $this->er = $er;
    }

    #[Route('/admin/requests', name: 'requests')]
    public function requests(Request $request): Response
    {
        $vet = null;
        if (!$this->isGranted('ROLE_CARER')) {
            /** @var User */
            $user = $this->getUser();
            $vet = $user->getVeterinary();
        }

        $filter = $request->query->get('filter', 'waiting');
        $requests = match ($filter) {
            'scheduled' => $this->rr->findAllScheduled($vet),
            'done' => $this->rr->findAllDone($vet),
            default => $this->rr->findAllWaiting($vet),
        };

        return $this->render('request/requests.html.twig', [
            'requests' => $requests,
            'filt' => $filter,
        ]);
    }

    #[Route('/admin/request/editor/{id?}', name: 'request_editor')]
    #[IsGranted('ROLE_CARER')]
    public function editor(Request $request, int|null $id): Response
    {
        $req = new EntityRequest();
        if ($id) {
            $req = $this->rr->findOneBy(['id' => $id]);
        }

        if (!$req) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(RequestType::class, $req);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User */
            $user = $this->getUser();

            $req
                ->setCaregiver($user->getCaregiver())
                ->setDateCreated(new \DateTime());
            $this->rr->save($req);
            return $this->redirectToRoute('requests');
        }

        return $this->render('request/editor.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/request/{id}', name: 'request')]
    public function request(int $id): Response
    {
        /** @var User */
        $user = $this->getUser();
        $request = $this->rr->findOneBy(['id' => $id]);
        if (!$request) {
            return $this->redirectToRoute('requests');
        }

        return $this->render('request/index.html.twig', [
            'request' => $request,
            'vet' => $user->getVeterinary() == $request->getVeterinary(),
        ]);
    }

    #[Route('/admin/examination/{id}', name: 'exam_editor')]
    #[IsGranted('ROLE_VET')]
    public function exam_editor(Request $request, int $id): Response
    {
        $req = new EntityRequest();
        if ($id) {
            $req = $this->rr->findOneBy(['id' => $id]);
        }

        if (!$req) {
            throw $this->createNotFoundException();
        }

        $exam = $req->getExamination() ?? new Examination();
        $form = $this->createForm(ExaminationType::class, $exam);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User */
            $user = $this->getUser();

            $exam->setRequest($req);
            $exam->setVeterinary($user->getVeterinary());
            $exam->setAnimal($req->getAnimal());

            if ($exam->getResult() != null && !empty($exam->getResult())) {
                $req->setDateFulfilled(new DateTime());
            }

            $exam->setResult($exam->getResult() ?? '');
            $this->er->save($exam);

            return $this->redirectToRoute('requests');
        }

        return $this->render('request/exam_editor.html.twig', [
            'request' => $req,
            'form' => $form,
        ]);
    }
}
