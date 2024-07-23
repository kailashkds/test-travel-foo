<?php

// src/Command/ExportDestinationsCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\HttpClient\HttpClient;

class ExportDestinationsCommand extends Command
{
    protected static $defaultName = 'app:export-destinations';

    public function __construct()
    {
        parent::__construct(self::$defaultName);
    }

    private const API_URL = 'http://travel_nginx/api/destinations'; // Replace with your API URL

    protected function configure(): void
    {
        $this
            ->setDescription('Exports all destinations from API to a CSV file.')
            ->setHelp('This command allows you to export all destinations from an API to a CSV file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = HttpClient::create();
        $csvFile = 'public/export/destinations.csv';
        $filesystem = new Filesystem();

        // Ensure the export directory exists
        $filesystem->mkdir(dirname($csvFile));

        // Open CSV file for writing
        $handle = fopen($csvFile, 'w');

        // Write header
        fputcsv($handle, ['name', 'description', 'price', 'duration']);

        $page = 1;
        $perPage = 50;
        $total = 0;

        do {
            $url = self::API_URL . '?page=' . $page . '&per_page=' . $perPage;

            try {
                $response = $client->request('GET', $url);
                $data = $response->toArray();

                if (isset($data['data']) && is_array($data['data'])) {
                    $destinations = $data['data'];

                    foreach ($destinations as $destination) {
                        fputcsv($handle, [
                            $destination['name'],
                            $destination['description'],
                            $destination['price'],
                            $destination['duration'],
                        ]);
                    }

                    $total = $data['pagination']['count'];
                    $page++;
                } else {
                    $output->writeln('No data found or invalid response format.');
                    break;
                }

            } catch (\Exception $e) {
                $output->writeln('An error occurred while fetching data: ' . $e->getMessage());
                return Command::FAILURE;
            }
        } while ($total > 0);

        fclose($handle);

        $output->writeln('Destinations exported to ' . $csvFile);

        return Command::SUCCESS;
    }
}
