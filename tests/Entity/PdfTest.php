<?php
// tests/Entity/PdfTest.php
namespace App\Tests\Entity;

use App\Entity\Pdf;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{
    public function testGetterAndSetter()
    {
        // Création d'une instance de l'entité Pdf
        $pdf = new Pdf();

        // Définition de données de test
        $title = 'PDF No1';
        $createdAt = new \DateTimeImmutable();
        $user = (new User())
            ->setEmail('email1@gmail.com')
            ->setPassword('password')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setSubscriptionEndAt(new \DateTime());


        // Utilisation des setters
        $pdf->setTitle($title);
        $pdf->setCreatedAt($createdAt);
        $pdf->setUser($user);

        // Vérification des getters
        $this->assertEquals($title, $pdf->getTitle());
        $this->assertEquals($createdAt, $pdf->getCreatedAt());
        $this->assertEquals($user, $pdf->getUser());
    }
}