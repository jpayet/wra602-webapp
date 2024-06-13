<?php

namespace App\Service;

use App\Entity\Pdf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use \Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\MailerInterface;

class PdfHistoryService
{
    private $entityManager;
    private $security;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, Security $security, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->mailer = $mailer;
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

    public function sendPdf($pdfContent, $filename, $user) {
        $username = $user->getFirstName() . ' ' . $user->getLastName();
        $email = (new TemplatedEmail())
            ->from('no-reply@pdfraptor.com')
            ->to($user->getEmail())
            ->subject('Votre PDF est prêt !')
            ->text('Bonjour, votre PDF est prêt !')
            ->htmlTemplate('generate_pdf/generated_pdf_email.html.twig')
            ->context([
                'username' => $username,
            ])
            ->attach($pdfContent, $filename, 'application/pdf');

        $this->mailer->send($email);
    }
}