<?php

namespace App\Service;

use App\Repository\PdfRepository;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PdfCheckerService
{
    private $pdfRepository;

    public function __construct(PdfRepository $pdfRepository)
    {
        $this->pdfRepository = $pdfRepository;
    }

    public function hasRichQuota($user): bool
    {
        $pdfCount = $this->pdfRepository->countUserPdfToday($user);
        $subscription = $user->getSubscription();
        $limitPdf = $subscription->getPdfLimit();

        if ($pdfCount >= $limitPdf) {
            return true;
        }

        return false;
    }
}