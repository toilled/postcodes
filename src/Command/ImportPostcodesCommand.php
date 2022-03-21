<?php
namespace App\Command;

use App\Entity\Postcode;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportPostcodesCommand extends Command
{
    protected static $defaultName = 'app:import-postcodes';
    protected static $defaultDescription = 'Import postcodes into the database.';
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;

        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('csv_file', InputArgument::REQUIRED, 'Full path of CSV file for postcodes');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityManager = $this->doctrine->getManager();

        $output->writeln("Opening postcodes CSV file.");

        $row = 1;
        if (($handle = fopen($input->getArgument('csv_File'), "r")) !== FALSE) {
            $headingLine = true;
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($headingLine) {
                    $headingLine = false;
                    continue;
                }

                $postcode = new Postcode();
                $postcode->setPostcode(str_replace(' ', '', $data[0]))
                    ->setLatitude((float)$data[42])
                    ->setLongitude((float)$data[43]);

                $entityManager->persist($postcode);
                $entityManager->flush();
                $output->write('.');
                $row++;
            }
            fclose($handle);
        } else {
            $output->writeln("Could not open file!");
            return Command::FAILURE;
        }

        $output->writeln("CSV file has $row rows.");

        return Command::SUCCESS;
    }
}