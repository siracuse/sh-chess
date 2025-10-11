<?php

namespace App\Controller\Admin;

use App\Repository\PuzzleRepository;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;


class AdminMainController extends AbstractController {

    #[Route('/admin', name: 'admin.index')]
    public function index(PuzzleRepository $puzzle_repository, ThemeRepository $theme_repository): Response
    {
        $nb_puzzle = count($puzzle_repository->findAll());
        $nb_theme = count($theme_repository->findAll());
        return $this->render('back/index.html.twig', [
            'nb_puzzle' => $nb_puzzle,
            'nb_theme' => $nb_theme
        ]);
    }

}