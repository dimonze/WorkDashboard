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

#[AsCommand(name: 'app:ScrapeData')]
class ScrapeData extends Command
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
//        $this->updateDbByDataArtPMData();
//        $this->updateRolesInContracts();
//        $this->calculateBudget();
        $mail = new MailService('exchange.dataart.com', 993,
            'universe\dhimenes',
            file_get_contents('/Users/dimonze/projects/WorkDashboard/var/password'));
        $mail->getMessages();
        $output->write("Command done");

        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     */
    protected function updateDbByDataArtPMData()
    {
        $results = new DataArtPMScraper([3770, 4942, 4939, 4941]);
        $tmpProjectStorage = [];
        $tmpEngineerStorage = [];
        $em = $this->doctrine->getManager();
        $emForContracts = $this->doctrine->getManager();

        foreach ($results->getProjectsList()->getAllProjects() as $project) {
            $projects = new Projects();

            if (null === ($contracts = $em->getRepository(Contracts::class)->findOneBy(['contractID' => $project->getRelatedContract()]))) {
                $contracts = new Contracts();
                $contracts->setContractID($project->getRelatedContract());
                $contracts->setName($project->getName());
                if ($project->getEndDate() <= new DateTime(Date("Y-m-d"))) {
                    $contracts->setStatus("Finished");
                } else {
                    $contracts->setStatus("Executed");
                }
                $contracts->setDescription($project->getName());
                $contracts->setSignedDate($project->getStartDate());
                $contracts->setStartDate($project->getStartDate());
                $contracts->setEndDate($project->getEndDate());
                $contracts->setOwner($project->getOwner());
                $emForContracts->persist($contracts);
                $emForContracts->flush();
            }

            $projects->setProjectID($project->getId());
            $projects->setProjectGroup($project->getProjectGroup());
            $projects->setName($project->getName());
            $projects->setStartDate($project->getStartDate());
            $projects->setEndDate($project->getEndDate());
            $projects->setClientName($project->getClientName());
            $projects->setOwner($project->getOwner());

            $projects->setRelatedContract($contracts);
            $tmpProjectStorage[$project->getId()] = $projects;

            $em->persist($projects);
        }

        foreach ($results->getEngineersList()->getAllEngineers() as $engineer) {
            $engineers = new Engineers();
            $engineers->setUserID($engineer->getID());
            $engineers->setName($engineer->getName());
            $engineers->setLocation($engineer->getLocation());
            $tmpEngineerStorage[$engineer->getID()] = $engineers;
            $em->persist($engineers);
        }

        foreach ($results->getAssignmentsList()->getAllAssignments() as $assignment) {
            $assignments = new Assignments();
            $assignments->setAssignmentID($assignment->getId());
            $assignments->setStartDate($assignment->getStartDate());
            $assignments->setEndDate($assignment->getEndDate());
            $assignments->setStatus($assignment->getStatus());
            $assignments->setRelatedProject($tmpProjectStorage[$assignment->getRelatedProject()]);
            $assignments->setRelatedEngineer($tmpEngineerStorage[$assignment->getRelatedEngineer()]);
            $assignments->setUtilization($assignment->getUtilization());
            $assignments->setRole($assignment->getRole());
            $assignments->setRate($assignment->getRate());
            $em->persist($assignments);
        }

        foreach ($results->getRateCard() as $item) {
            $roles = new Roles();
            $roles->setName($item["Role"]);
            $roles->setPrice($item["Rate"]);
            $em->persist($roles);
        }
        $em->flush();
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

    private function calculateBudget()
    {
        $em = $this->doctrine->getManager();
        $contracts = $em->getRepository(Contracts::class)->findAll();
        foreach ($contracts as $contract) {
            $contract->setBudgetCap(1);
            $em->persist($contract);
            $em->flush();

        }
        unset($contract);
        unset($em);
    }
}