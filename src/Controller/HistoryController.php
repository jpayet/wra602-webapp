<?php

namespace App\Controller;

use App\Repository\PdfRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HistoryController extends AbstractController
{
    private PdfRepository $pdfRepository;

    public function __construct(PdfRepository $pdfRepository)
    {
        $this->pdfRepository = $pdfRepository;
    }

    #[Route('/history', name: 'app_history')]
    public function index(): Response
    {
        $pdfs = $this->pdfRepository->findBy(['user' => $this->getUser()]);

        return $this->render('index/history.html.twig', [
            'pdfs' => $pdfs
        ]);
    }
}
