<?php

namespace App\Command;

use App\Service\SwapiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'starwars:import',
)]
class StarwarsImportCommand extends Command
{
    private $em = null;
    private $client = null;

    public function __construct(EntityManagerInterface $em, HttpClientInterface $client)
    {
        parent::__construct();

        $this->em = $em;
        $this->client = $client;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $swapiService = new SwapiService($this->em, $this->client);
            $swapiService->import();

            $io->success('Successfully imported data from swapi.dev');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('An error occurred while importing data from swapi.dev');
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
