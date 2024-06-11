<?php

namespace App\Controller;

use App\Service\GotenbergService;
use Doctrine\DBAL\Types\Types;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GeneratePdfController extends AbstractController
{
    private $pdfService;
    private string $publicTempAbsoluteDirectory;

    public function __construct(GotenbergService $pdfService, string $publicTempAbsoluteDirectory)
    {
        $this->pdfService = $pdfService;
        $this->publicTempAbsoluteDirectory = $publicTempAbsoluteDirectory;

    }

    #[Route('/pdf/generate/url', name: 'app_pdf_generate_url')]
    public function generatePdfUrl(ParameterBagInterface $parameterBag, Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('url', null, ['required' => true])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form->getData()['url'];

            $pdf = $this->pdfService->generatePdfUrl($parameterBag, $url);

            return new Response($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="my-generated-pdf.pdf"',
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

            $pdf = $this->pdfService->generatePdfHtml($parameterBag, $filePath);

            unlink($filePath);
            rmdir($tempDir);

            return new Response($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="my-generated-pdf.pdf"',
            ]);
        }

        return $this->render('generate_pdf/pdf_by_html.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}