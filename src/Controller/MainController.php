<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Paste;
use App\Form\PasteType;
use App\Repository\PasteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    private const LIMIT_PASTES_SHOWN = 10;

    #[Route('/', name: 'app_main')]
    public function index(Request $request, PasteRepository $pasteRepository): Response
    {

        $page = max(1, (int)$request->query->get('page', 1));
        $paginator = $pasteRepository->findPublicPastesPaginated($page, self::LIMIT_PASTES_SHOWN);
        $latest_pastes = $pasteRepository->findLatestPublicPastes(self::LIMIT_PASTES_SHOWN);

        $paste = new Paste();
        $form = $this->createForm(PasteType::class, $paste);

        return $this->render('main/index.html.twig', [
            'form' => $form,
            'latest_pastes' => $latest_pastes,
            'pastes' => $paginator,
            'currentPage' => $page,
            'totalPages' => ceil($paginator->count() / self::LIMIT_PASTES_SHOWN),
        ]);
    }
}
