<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/admin/theme', name:'admin.theme.')]
class AdminThemeController extends AbstractController {

    #[Route('/', name: 'index')]
    public function index(ThemeRepository $repository): Response
    {
        $themes = $repository->findAll();
        return $this->render('back/theme/index.html.twig', [
            'themes' => $themes,
        ]);
    }


    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($theme);
            $em->flush();
            $this->addFlash('success', "Le a bien été ajoutée");
            return $this->redirectToRoute('admin.theme.index');
        }
        return $this->render('back/theme/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Theme $theme, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', "Le theme a bien été modifiée");
            return $this->redirectToRoute('admin.theme.index');
        }
        return $this->render('back/theme/edit.html.twig', [
            'form' => $form,
            'theme' => $theme
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Theme $theme, EntityManagerInterface $em)
    {
        $em->remove($theme);
        $em->flush();
        $this->addFlash('success', "Le theme a bien été supprimée");
        return $this->redirectToRoute('admin.theme.index');
    }

}