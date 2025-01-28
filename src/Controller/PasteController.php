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

            $expirationChoice = $form->get('expiration')->getData();
            $this->handleExpiration($paste, $expirationChoice);
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

    #[Route('/{uuid}', name: 'paste_show', methods: ['GET'])]
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


    private function handleExpiration(Paste $paste, ?string $expirationChoice): void
    {
        if (!$expirationChoice) {
            $paste->setExpiration(null);
            return;
        }

        $modifiers = [
            '1 hour' => '+1 hour',
            '1 day' => '+1 day',
            '1 week' => '+1 week',
            '1 month' => '+1 month'
        ];

        if (isset($modifiers[$expirationChoice])) {
            $paste->setExpiration(
                (new \DateTime())->modify($modifiers[$expirationChoice])
            );
        }
    }
}
