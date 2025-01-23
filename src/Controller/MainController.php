<?php

namespace App\Controller;

use App\Entity\Paste;
use App\Form\CreatePasteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{




    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        $paste = new Paste();
        $form = $this->createForm(CreatePasteType::class, $paste);
        return $this->render('main/index.html.twig', [
            'form' => $form,
        ]);
    }
}
