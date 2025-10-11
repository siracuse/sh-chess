<?php

namespace App\Controller;

use App\Entity\Puzzle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WoodpeckerController extends AbstractController
{
    #[Route('/woodpecker', name: 'woodpecker')]
    public function index(EntityManagerInterface $em): Response
    {
        $firstPuzzle = $em->getRepository(Puzzle::class)->find(7);
        return $this->render('woodpecker/index.html.twig', [
            'puzzle' => $firstPuzzle
        ]);
    }

    #[Route('/woodpecker/next', name: 'woodpecker_next', methods:['POST'])]
    public function nextPuzzle(Request $request, EntityManagerInterface $em): Response
    {
        $currentId = $request->request->get('currentId');
        $puzzles = $em->getRepository(Puzzle::class)->findAll();
        $nextPuzzle = null;

        foreach ($puzzles as $p) {
            if ($p->getId() > $currentId) {
                $nextPuzzle = $p;
                break;
            }
        }
        if (!$nextPuzzle) {
            return $this->json(['done' => true]); // plus de puzzle
        }

        return $this->json([
            'id' => $nextPuzzle->getId(),
            'fen' => $nextPuzzle->getFen(),
            'solution' => $nextPuzzle->getSolution(),
            // 'message' => $nextPuzzle->getMessage()
        ]);
    }
}
