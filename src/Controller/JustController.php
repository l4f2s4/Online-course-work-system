<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JustController extends AbstractController
{
    /**
     * @Route("/error", name="just")
     */
    public function index(): Response
    {
        return $this->render('error/errorpage.html.twig', [
            'controller_name' => 'JustController',
        ]);
    }
}
