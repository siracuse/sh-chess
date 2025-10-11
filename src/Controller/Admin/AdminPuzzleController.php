<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Puzzle;
use App\Form\PuzzleType;
use App\Repository\PuzzleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/admin/puzzle', name:'admin.puzzle.')]
class AdminPuzzleController extends AbstractController {

    #[Route('/', name: 'index')]
    public function index(PuzzleRepository $repository): Response
    {
        $puzzles = $repository->findAll();
        return $this->render('back/puzzle/index.html.twig', [
            'puzzles' => $puzzles,
        ]);
    }


    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $puzzle = new Puzzle();
        $form = $this->createForm(PuzzleType::class, $puzzle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($puzzle);
            $em->flush();
            $this->addFlash('success', "Le a bien été ajoutée");
            return $this->redirectToRoute('admin.puzzle.index');
        }
        return $this->render('back/puzzle/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Puzzle $puzzle, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PuzzleType::class, $puzzle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', "Le puzzle a bien été modifiée");
            return $this->redirectToRoute('admin.puzzle.index');
        }
        return $this->render('back/puzzle/edit.html.twig', [
            'form' => $form,
            'puzzle' => $puzzle
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Puzzle $puzzle, EntityManagerInterface $em)
    {
        $em->remove($puzzle);
        $em->flush();
        $this->addFlash('success', "Le puzzle a bien été supprimée");
        return $this->redirectToRoute('admin.puzzle.index');
    }
}