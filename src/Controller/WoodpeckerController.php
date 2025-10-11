<?php

namespace App\Controller;

use App\Entity\Puzzle;
use App\Entity\Theme;
use App\Repository\PuzzleRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WoodpeckerController extends AbstractController
{
    // #[Route('/woodpecker', name: 'woodpecker')]
    // public function index(EntityManagerInterface $em): Response
    // {
    //     $firstPuzzle = $em->getRepository(Puzzle::class)->find(7);
    //     return $this->render('woodpecker/index.html.twig', [
    //         'puzzle' => $firstPuzzle
    //     ]);
    // }

    #[Route('/woodpecker', name: 'woodpecker')]
    public function index(ThemeRepository $theme_repository): Response
    {
        $themes = $theme_repository->findAll();
        return $this->render('front/woodpecker.html.twig', [
            'themes' => $themes
        ]);
    }

    #[Route('woodpecker/theme/{id}', name: 'woodpecker.theme')]
    public function run(Theme $theme, PuzzleRepository $puzzle_repository, ) {
        $first_puzzle = $puzzle_repository->findOneByThemeId($theme->getId());
        
        $solutionString = $first_puzzle->getSolution();
        $solutionMove = array_map('trim', explode(',', $solutionString));

        return $this->render('front/puzzle.html.twig', [
            'puzzle' => $first_puzzle,
            'solutionMove' => $solutionMove,
            'theme' => $theme
        ]);
    }

    #[Route('/woodpecker/next', name: 'woodpecker_next', methods:['POST'])]
    public function nextPuzzle(Request $request, PuzzleRepository $puzzle_repository): Response
    {
        $currentId = $request->request->get('currentId');
        $puzzles = $puzzle_repository->findAll();
        $nextPuzzle = null;

        foreach ($puzzles as $p) {
            if ($p->getId() > $currentId) {
                $nextPuzzle = $p;
                break;
            }
        }
        if (!$nextPuzzle) {
            return $this->json(['done' => true]);
        }

        return $this->json([
            'id' => $nextPuzzle->getId(),
            'fen' => $nextPuzzle->getFen(),
            'solution' => array_map('trim', explode(',', $p->getSolution())),
        ]);
    }
}
