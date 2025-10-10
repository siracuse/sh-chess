<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WoodpeckerController extends AbstractController
{
    #[Route('/woodpecker', name: 'woodpecker')]
    public function index(): Response
    {
        return $this->render('woodpecker/index.html.twig');
    }
}
