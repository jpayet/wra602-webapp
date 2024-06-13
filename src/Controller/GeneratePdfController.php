<?php

namespace App\Controller;

use App\Repository\PdfRepository;
use App\Service\GotenbergService;
use App\Service\PdfCheckerService;
use App\Service\PdfFileNameGeneratorService;
use App\Service\PdfHistoryService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GeneratePdfController extends AbstractController
{
    private $gotenbergService;
    private $pdfCheckerService;
    private $pdfHistoryService;
    private $pdfFileNameGeneratorService;

    private string $publicTempAbsoluteDirectory;

    public function __construct(
        GotenbergService $gotenbergService,
        PdfCheckerService $pdfCheckerService,
        PdfRepository $pdfRepository,
        PdfHistoryService $pdfHistoryService,
        PdfFileNameGeneratorService $pdfFileNameGeneratorService,
        string $publicTempAbsoluteDirectory
    )
    {
        $this->gotenbergService = $gotenbergService;
        $this->pdfCheckerService = $pdfCheckerService;
        $this->pdfHistoryService = $pdfHistoryService;
        $this->pdfFileNameGeneratorService = $pdfFileNameGeneratorService;
        $this->pdfRepository = $pdfRepository;
        $this->publicTempAbsoluteDirectory = $publicTempAbsoluteDirectory;

    }

    #[Route('/pdf/generate/url', name: 'app_pdf_generate_url')]
    public function generatePdfUrl(ParameterBagInterface $parameterBag, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->add('url', null, ['required' => true])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form->getData()['url'];

            //Check si l'user à atteint son quota de génération
            if ($this->pdfCheckerService->hasRichQuota($this->getUser())) {
                $this->addFlash('danger', 'Vous avez atteint votre quota de génération de PDFs pour aujourd\'hui (soit '. $this->getUser()->getSubscription()->getPdfLimit() .'/jour).');
                return $this->redirectToRoute('app_pdf_generate_url');
            }

            //Générer le pdf
            $pdf_file = $this->gotenbergService->generatePdfUrl($parameterBag, $url);
            $filename = $this->pdfFileNameGeneratorService->generateFileName();

            //Enregistrement dans la table pdf pour l'historique
            if ($pdf_file != null) {
                $this->pdfHistoryService->savePdf($filename, "url");
            }

            return new Response($pdf_file, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);
        }

        return $this->render('generate_pdf/pdf_by_url.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/pdf/generate/html', name: 'app_pdf_generate_html')]
    public function generatePdfHtml(ParameterBagInterface $parameterBag, Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('file', FileType::class, ['required' => true, 'label' => 'Fichier'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            $tempDir = $this->publicTempAbsoluteDirectory . uniqid();
            mkdir($tempDir, 0777, true);

            $file->move($tempDir, 'index.html');
            $filePath = $tempDir . '/' . 'index.html';
            chmod($filePath, 0777);

            if ($this->pdfCheckerService->hasRichQuota($this->getUser())) {
                $this->addFlash('danger', 'Vous avez atteint votre quota de génération de PDFs pour aujourd\'hui (soit '. $this->getUser()->getSubscription()->getPdfLimit() .'/jour).');
                return $this->redirectToRoute('app_pdf_generate_html');
            }

            $pdf_file = $this->gotenbergService->generatePdfHtml($parameterBag, $filePath);
            $filename = $this->pdfFileNameGeneratorService->generateFileName();

            if ($pdf_file != null) {
                $this->pdfHistoryService->savePdf($filename, "html");
            }

            unlink($filePath);
            rmdir($tempDir);

            return new Response($pdf_file, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename .'"',
            ]);
        }

        return $this->render('generate_pdf/pdf_by_html.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}