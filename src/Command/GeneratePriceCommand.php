<?php

namespace App\Command;

use App\Repository\ItemRepository;
use App\Service\PriceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate:price',
)]
class GeneratePriceCommand extends Command
{
    public function __construct(
        private readonly ItemRepository $itemRepository,
        private readonly EntityManagerInterface $em,
        private readonly PriceService $priceService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $items = $this->itemRepository->findAll();
        $progressBar = $io->createProgressBar(count($items));
        $progressBar->start();

        $this->priceService->generatePricesForItems($items);
        
       // $this->em->flush();
        $progressBar->finish();
        $io->success('Price generated');
        return Command::SUCCESS;
    }
}