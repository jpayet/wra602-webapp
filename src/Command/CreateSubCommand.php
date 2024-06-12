<?php

namespace App\Command;

use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-sub',
    description: 'Création des différents abonnements disponibles pour les utilisateurs'
)]
class CreateSubCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

//    protected function configure(): void
//    {
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
//    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Création des abonnements');

        $l_subscriptions = [
            [
                'title' => 'Baby Raptor',
                'price' => 0,
                'pdf_limit' => 10,
                'description' => 'Abonnement gratuit qui permet de générer 2 PDF par jour',
                'media' => '',
            ],
            [
                'title' => 'Raptor Junior',
                'price' => 9.99,
                'pdf_limit' => 25,
                'description' => 'Abonnement plus avancée qui octroie 25 PDF par jour',
                'media' => '',
            ],
            [
                'title' => 'Giant Raptor',
                'price' => 19.99,
                'pdf_limit' => 100,
                'description' => 'Abonnement premium qui permet de générer 100 PDF par jour',
                'media' => '',
            ],
        ];

        foreach ($l_subscriptions as $subscription) {
            $sub = (new Subscription())
                ->setTitle($subscription['title'])
                ->setPrice($subscription['price'])
                ->setPdfLimit($subscription['pdf_limit'])
                ->setDescription($subscription['description'])
                ->setMedia($subscription['media'] ?? null);
            ;
            $this->entityManager->persist($sub);
        }
        $this->entityManager->flush();

        $io->success('Les 3 abonnements on été créés avec succès');

        return Command::SUCCESS;
    }
}
