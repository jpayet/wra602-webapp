<?php
// tests/Entity/SubscriptionTest.php
namespace App\Tests\Entity;

use App\Entity\Subscription;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function testGetterAndSetter()
    {
        // Création d'une instance de l'entité Subscription
        $subscription = new Subscription();

        // Définition de données de test
        $title = 'Formule basique';
        $description = 'Formule basique avec accès à un 1 PDF par mois';
        $pdfLimit = 1;
        $price = 5.99;
        $media = 'image';

        // Utilisation des setters
        $subscription->setTitle($title);
        $subscription->setDescription($description);
        $subscription->setPdfLimit($pdfLimit);
        $subscription->setPrice($price);
        $subscription->setMedia($media);

        // Vérification des getters
        $this->assertEquals($title, $subscription->getTitle());
        $this->assertEquals($description, $subscription->getDescription());
        $this->assertEquals($pdfLimit, $subscription->getPdfLimit());
        $this->assertEquals($price, $subscription->getPrice());
        $this->assertEquals($media, $subscription->getMedia());
    }
}