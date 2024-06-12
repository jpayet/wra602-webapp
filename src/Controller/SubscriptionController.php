<?php

namespace App\Controller;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscriptionController extends AbstractController
{
    private SubscriptionRepository $subscriptionRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        SubscriptionRepository $subscriptionRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/subscription', name: 'app_subscription_list')]
    public function index(): Response
    {
        $subscriptions = $this->subscriptionRepository->findAll();

        return $this->render('index/subscription.html.twig', [
            'subscriptions' => $subscriptions
        ]);
    }

    #[Route('/subscription/{id}', name: 'app_subscription_switch')]
    public function switch(int $id): Response
    {
        $subscription = $this->subscriptionRepository->find($id);

        $this->getUser()->setSubscription($subscription);

        $this->entityManager->flush();

        return $this->redirectToRoute('app_subscription_list');
    }
}
