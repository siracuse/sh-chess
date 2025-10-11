<?php

namespace App\Controller;

use App\Entity\Statistic;
use App\Entity\Theme;
use App\Repository\PuzzleRepository;
use App\Repository\ThemeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WoodpeckerController extends AbstractController
{

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
        $themeId = $request->request->get('themeId');
        $puzzles = $puzzle_repository->findAllByThemeId($themeId);
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

    #[Route('/woodpecker/save-stats', name: 'woodpecker_save_stats', methods: ['POST'])]
    public function saveStats(Request $request, EntityManagerInterface $em, ThemeRepository $theme_repository): JsonResponse
    {
        $themeId = $request->request->get('themeId');
        $totalTime = $request->request->get('totalTime');
        $errors = $request->request->get('errors');

        $stats = new Statistic();
        $theme = $em->getRepository(Theme::class)->find($themeId);
        $stats->setTheme($theme);
        $stats->setDatetime(new DateTime());
        $stats->setTime((int)$totalTime);
        $stats->setNbErreur((int)$errors);
        $stats->setUser($this->getUser()); 
        $em->persist($stats);
        $em->flush();

        return $this->json(['success' => true]);
    }
}
