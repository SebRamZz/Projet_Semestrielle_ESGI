<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\DrivingSchool;
use App\Form\ContractType;
use App\Repository\ContractRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/driving-school/{idS}/contract')]
class ContractController extends AbstractController
{
    #[Route('/', name: 'app_contract_index', methods: ['GET'])]
    public function index(ContractRepository $contractRepository, DrivingSchool $idS): Response
    {
        return $this->render('contract/index.html.twig', [
            'contracts' => $contractRepository->findAll(),
            'drivingSchool' => $idS->getId(),
        ]);
    }

    #[Route('/new', name: 'app_contract_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, DrivingSchool $idS): Response
    {
        $session = $request->getSession();
        $schoolSelected = $session->get('driving-school-selected');

        $contract = new Contract();
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contract->setDrivingSchool($idS);
            $entityManager->persist($contract);
            $entityManager->flush();

            return $this->redirectToRoute('app_contract_index', ["idS" => $idS->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contract/new.html.twig', [
            'drivingSchool' => $schoolSelected,
            'contract' => $contract,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contract_show', methods: ['GET'])]
    public function show(Contract $contract, DrivingSchool $idS): Response
    {
        return $this->render('contract/show.html.twig', [
            'drivingSchool' => $idS->getId(),
            'contract' => $contract,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contract_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contract $contract, EntityManagerInterface $entityManager, DrivingSchool $idS): Response
    {
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contract/edit.html.twig', [
            'drivingSchool' => $idS->getId(),
            'contract' => $contract,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contract_delete', methods: ['POST'])]
    public function delete(Request $request, Contract $contract, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contract->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contract);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contract_index', [], Response::HTTP_SEE_OTHER);
    }
}
