<?php

namespace App\Controller;

use App\Repository\StatisticRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StatController extends AbstractController
{
    #[Route('/stat', name: 'stat')]
    public function index(StatisticRepository $statistic_repository): Response
    {
        $statistics = $statistic_repository->findAllByUser($this->getUser());
        return $this->render('front/stat.html.twig', [
            'stats' => $statistics
        ]);
    }
}
