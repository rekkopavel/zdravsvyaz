<?php

namespace App\Controller;

use App\Entity\Paste;
use App\Form\PasteType;
use App\Repository\PasteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/paste')]
final class PasteController extends AbstractController
{
    #[Route('/save', name: 'save_paste', methods: [ 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paste = new Paste();
        $form = $this->createForm(PasteType::class, $paste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paste);
            $entityManager->flush();
//TODO сделать вывод сообщения со ссылкой
            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paste/new.html.twig', [
            'paste' => $paste,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_paste_show', methods: ['GET'])]
    public function show(PasteRepository $pasteRepository, $uuid): Response
    {
        $paste = $pasteRepository->findByUuidNotExpired($uuid);

        if (!$paste) {
            throw $this->createNotFoundException('Paste not found');
        }
        return $this->render('paste/show.html.twig', [
            'paste' => $paste,
        ]);
    }


}
