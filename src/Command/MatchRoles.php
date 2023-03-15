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

#[AsCommand(name: 'app:MatchRoles')]
class MatchRoles extends Command
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
        $this->updateRolesInContracts();
        $output->write("Command done");

        return Command::SUCCESS;
    }

    private function updateRolesInContracts()
    {
        $em = $this->doctrine->getManager();
        $contracts = $em->getRepository(Contracts::class)->findAll();
        foreach ($contracts as $contract) {
            foreach ($contract->getRelatedProjects() as $project) {
                foreach ($project->getRelatedAssignments() as $assignment) {
                    if ("1" === $assignment->getStatus()) {
                        $assignedRole = new AssignedRoles();
                        $assignedRole->setRelatedContract($contract);
                        $assignedRole->setRole($em->getRepository(Roles::class)->findOneBy(['name' => $assignment->getRole()]));
                        $assignedRole->setStartDate($assignment->getStartDate() != null ? $assignment->getStartDate() : $project->getStartDate());
                        $assignedRole->setEndDate($assignment->getEndDate() != null ? $assignment->getEndDate() : $project->getEndDate());
                        $assignedRole->setUtilization(1);
                        $em->persist($assignedRole);

                    }
                }

            }
        }
        $em->flush();
    }
}