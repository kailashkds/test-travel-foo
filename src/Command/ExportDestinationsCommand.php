<?php

namespace App\Command;

use App\Repository\DestinationRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use League\Csv\Writer;
use Symfony\Component\Filesystem\Filesystem;

class ExportDestinationsCommand extends Command
{
    protected static $defaultName = 'app:export-destinations';

    private $destinationRepository;
    private $publicDir;

    public function __construct(DestinationRepository $destinationRepository, string $publicDir)
    {
        $this->destinationRepository = $destinationRepository;
        $this->publicDir = $publicDir;
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fetches all destinations from the database and exports them to a CSV file.')
            ->addArgument('filename', InputArgument::OPTIONAL, 'The filename for the CSV file', 'destinations.csv');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $destinations = $this->destinationRepository->findAll();

        if (empty($destinations)) {
            $output->writeln('<info>No destinations found in the database.</info>');
            return Command::SUCCESS;
        }

        $csv = Writer::createFromString('');
        $csv->insertOne(['name', 'description', 'price', 'duration']);

        foreach ($destinations as $destination) {
            $csv->insertOne([
                $destination->getName(),
                $destination->getDescription(),
                $destination->getPrice(),
                $destination->getDuration(),
            ]);
        }

        $filesystem = new Filesystem();
        $exportDir = $this->publicDir . '/export';
        if (!$filesystem->exists($exportDir)) {
            $filesystem->mkdir($exportDir);
        }

        $filename = $input->getArgument('filename');
        $filePath = $exportDir . '/' . $filename;
        file_put_contents($filePath, $csv->toString());

        $output->writeln("<info>Exported destinations to $filePath</info>");

        return Command::SUCCESS;
    }
}
