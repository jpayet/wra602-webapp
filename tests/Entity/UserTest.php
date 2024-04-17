<?php
// tests/Entity/UserTest.php
namespace App\Tests\Entity;

use App\Entity\Subscription;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetterAndSetter()
    {
        // Création d'une instance de l'entité User
        $user = new User();

        // Définition de données de test
        $email = 'test@test.com';
        $firstName = 'John';
        $lastName = 'Doe';
        $createdAt = new \DateTimeImmutable();
        $updatedAt = new \DateTimeImmutable();
        $subscriptionEndAt = new \DateTime();
        $roles = ['ROLE_USER'];
        $password = 'password';
        $subscription = (new Subscription())
            ->setTitle('Formule basique')
            ->setDescription('Formule basique avec accès à un 1 PDF par mois')
            ->setPdfLimit(1)
            ->setPrice(5.99)
            ->setMedia('image');

        // Utilisation des setters
        $user->setCreatedAt($createdAt);
        $user->setUpdatedAt($updatedAt);
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setSubscriptionEndAt($subscriptionEndAt);
        $user->setRoles($roles);
        $user->setPassword($password);
        $user->setSubscription($subscription);

        // Vérification des getters
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($createdAt, $user->getCreatedAt());
        $this->assertEquals($updatedAt, $user->getUpdatedAt());
        $this->assertEquals($subscriptionEndAt, $user->getSubscriptionEndAt());
        $this->assertEquals($roles, $user->getRoles());
        $this->assertEquals($subscription, $user->getSubscription());
    }
}