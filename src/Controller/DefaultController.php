<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_index')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'accueil',
        ]);
    }

    #[Route('/design-guide', name: 'design_guide')]
    public function design_guide(): Response
    {
        return $this->render('default/design_guide.html.twig');
    }
}
