<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Paste;
use App\Form\PasteType;
use App\Repository\PasteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/paste')]
final class PasteController extends AbstractController
{
    #[Route('/save', name: 'save_paste', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $paste = new Paste();
        $form = $this->createForm(PasteType::class, $paste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paste = $form->getData();
            $entityManager->persist($paste);
            $entityManager->flush();
            return new JsonResponse([
                'status' => 'success',
                'message' => 'Данные успешно сохранены!'
            ], 200);
        }

        return new JsonResponse([
            'status' => 'error',
            'message' => 'Ошибка при сохранении данных.'
        ], 400);
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
