<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Paste;
use App\Responses;
use App\Form\PasteType;
use App\Repository\PasteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/paste')]
final class PasteController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PasteRepository $pasteRepository,
        private readonly Responses\PasteResponse $pasteResponse
    ) {}

    #[Route('/save', name: 'save_paste', methods: ['POST'])]
    public function save(Request $request): JsonResponse
    {
        $paste = new Paste();
        $form = $this->createForm(PasteType::class, $paste);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->pasteResponse->createErrorResponse('Ошибка при сохранении данных.');
        }

        $expirationChoice = $form->get('expiration')->getData();
        $this->setExpiration($paste, $expirationChoice);

        $this->entityManager->persist($paste);
        $this->entityManager->flush();

        return $this->pasteResponse->createSuccessResponse('Данные успешно сохранены!');
    }

    #[Route('/{uuid}', name: 'paste_show', methods: ['GET'])]
    public function show(string $uuid): Response
    {
        $paste = $this->pasteRepository->findByUuidNotExpired($uuid);

        if (!$paste) {
            throw $this->createNotFoundException('Paste not found');
        }

        return $this->render('paste/show.html.twig', [
            'paste' => $paste,
        ]);
    }

    private function setExpiration(Paste $paste, ?string $expirationChoice): void
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

        if (array_key_exists($expirationChoice, $modifiers)) {
            $paste->setExpiration(
                (new \DateTime())->modify($modifiers[$expirationChoice])
            );
        }
    }


}
