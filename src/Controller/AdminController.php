<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChooseDateType;
use App\Form\RegistrationFormType;
use App\Form\RegistrationUpdateFormType;
use App\Model\DateData;
use App\Repository\ClientRepository;
use App\Repository\ContractRepository;
use App\Repository\InvoiceRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/', name: 'app_admin_index')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $session = $request->getSession();
        $schoolSelected = $session->get('driving-school-selected');

        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
            'drivingSchool' => $schoolSelected,
        ]);
    }


    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerService $mailer): Response
    {
        $session = $request->getSession();
        $schoolSelected = $session->get('driving-school-selected');

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Set le role du user par défaut
        $roles[] = "ROLE_BOSS";
        $user->setRoles($roles);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash("success", "L'utilisateur à été créé avec succès");

            $mailer->sendConfirmationMail($this->emailVerifier, $this->getParameter('address_mailer'), $this->getParameter('kernel.project_dir') . '/assets/images/driving-school.png', $user);

        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(),
            'drivingSchool' => $schoolSelected,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, Request $request): Response
    {

        $session = $request->getSession();
        $schoolSelected = $session->get('driving-school-selected');
        return $this->render('admin/show.html.twig', [
            'user' => $user,
            'drivingSchool' => $schoolSelected,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {

        $session = $request->getSession();
        $schoolSelected = $session->get('driving-school-selected');

        $form = $this->createForm(RegistrationUpdateFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash("success", "L'utilisateur à été modifié avec succès");

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'drivingSchool' => $schoolSelected,
        ]);
    }


    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    #[Security('is_granted("ROLE_ADMIN") or (is_granted("ROLE_BOSS"))')]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}