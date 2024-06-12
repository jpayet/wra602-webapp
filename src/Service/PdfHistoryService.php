<?php

namespace App\Service;

use App\Entity\Pdf;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Bundle\SecurityBundle\Security;
class PdfHistoryService
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function savePdf($filename, $method)
    {
        $pdf = new Pdf();
        $pdf->setTitle($filename);
        $pdf->setMethod($method);
        $pdf->setCreatedAt(new \DateTimeImmutable());
        $pdf->setUser($this->security->getUser());

        $this->entityManager->persist($pdf);
        $this->entityManager->flush();
    }
}