<?php

namespace App\Command;

use App\Command\Helpers\Utility\MailService;
use App\Command\Scrapers\DataArtPM\DataArtPMScraper;
use App\Entity\AssignedRoles;
use App\Entity\Assignments;
use App\Entity\Contracts;
use App\Entity\Engineers;
use App\Entity\Projects;
use App\Entity\Roles;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:CheckMails')]
class CheckEmails extends Command
{
    private LoggerInterface $logger;
    private ManagerRegistry $doctrine;

    public function __construct(LoggerInterface $logger, ManagerRegistry $doctrine)
    {
        $this->logger = $logger;
        $this->doctrine = $doctrine;

        // you *must* call the parent constructor
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('This script scrape data from different sources to internal DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write("Command started\n");
        $mail = new MailService('exchange.dataart.com', 993,
            'universe\dhimenes',
            file_get_contents('/Users/dimonze/projects/WorkDashboard/var/password'));
        $mail->getMessages();
        $output->write("Command done");

        return Command::SUCCESS;
    }
}