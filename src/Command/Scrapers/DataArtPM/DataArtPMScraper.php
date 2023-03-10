<?php

namespace App\Command\Scrapers\DataArtPM;

use App\Command\Helpers\Http\RestHelper;
use App\Command\Helpers\Parsers\GeneralParser;
use App\Command\Scrapers\DataArtPM\Entities\Assignment;
use App\Command\Scrapers\DataArtPM\Entities\Engineer;
use App\Command\Scrapers\DataArtPM\Entities\Project;
use App\Command\Scrapers\DataArtPM\Storages\AssignmentsList;
use App\Command\Scrapers\DataArtPM\Storages\ContractsLists;
use App\Command\Scrapers\DataArtPM\Storages\EngineersList;
use App\Command\Scrapers\DataArtPM\Storages\ProjectsList;

class DataArtPMScraper
{
    private const PROJECTS_LIST = "https://pmaccounting.dataart.com/ProjectsJson";
    private const PROJECTS_LIST_QUERY = "projectName=&projectGroup=%d&customer=-1&startDate=,&estimatedFinish=,&finish=,&billStatus=0,4,5,2,1,3,6&status=0,1&legalEntity=-1";
    private const PROJECTS_DETAILS_RATES = "https://pmaccounting.dataart.com/Rates/ShowRates?projectId=%d";
    private const USER_NAME = "dhimenes";

    private ProjectsList $projectsList;
    private EngineersList $engineersList;
    private AssignmentsList $assignmentsList;
    private ContractsLists $contractsLists;
    private string $password;
    private array $rateCard = [];

    public function __construct(array $listOfGroupIDs)
    {
        $this->projectsList = new ProjectsList();
        $this->engineersList = new EngineersList();
        $this->assignmentsList = new AssignmentsList();
        $this->contractsLists = new ContractsLists();
        $this->password = file_get_contents($_SERVER["PWD"] . "/var/password");
        foreach ($listOfGroupIDs as $groupID) {
            $this->scrapeGroup($groupID);
        }
    }

    private function scrapeGroup(int $groupID): mixed
    {
        $rawProjects = RestHelper::postRequest(self::PROJECTS_LIST,
            sprintf(self::PROJECTS_LIST_QUERY, $groupID),
            self::USER_NAME, $this->password, true);
        foreach ($rawProjects as $project){
            $this->extractData($project);
        }

        return $rawProjects;
    }

    private function extractData($projectData): void
    {
        $rawProjectDetails = RestHelper::getRequest(sprintf(self::PROJECTS_DETAILS_RATES, $projectData["ProjectId"]),
            self::USER_NAME, $this->password, true)["projectRates"];
        $this->extractProjects($projectData, $rawProjectDetails);
        $this->extractEngineers($projectData, $rawProjectDetails);
        $this->extractAssignments($projectData, $rawProjectDetails);
    }

    private function extractProjects($projectData, $rawProjectDetails): void
    {
        $project = new Project();
        $project->setId($projectData["ProjectId"]);
        $project->setProjectGroup($projectData["ProjectGroupName"]);
        $project->setOwner($projectData["ProjectLeaderName"]);
        $project->setName(GeneralParser::removeCfaPrefix($projectData["ProjectName"]));
        $project->setClientName($projectData["ProjectCustomerName"]);
        $project->setStartDate($projectData["ProjectStartDate"]);
        $project->setEndDate($projectData["ProjectEstimatedEndDate"]);
        $project->setRelatedAssignments($rawProjectDetails);
        $project->setRelatedContract($projectData["ProjectName"]);
        $this->projectsList->addProject($project);
    }

    private function extractEngineers($projectData, $rawProjectDetails): void
    {
        foreach ($rawProjectDetails as $detail) {
            $engineer = new Engineer();
            $engineer->setId($detail["StaffId"]);
            $engineer->setName($detail["StaffName"]);
            $engineer->setRoles(null);
            $engineer->setLocation($detail["StaffLocation"]);
            $engineer->setRelatedAssignments($this->collectAssignmentForUser($detail["StaffId"], $detail["ProjectStaffId"]));
            $engineer->setSelfRate(null);
            $this->engineersList->addEngineer($engineer);
        }
    }

    private function extractAssignments($projectData, $rawProjectDetails): void
    {
        foreach ($rawProjectDetails as $detail) {
            $assignment = new Assignment();
            $assignment->setId($detail["ProjectStaffId"]);
            $assignment->setStartDate($detail["FirstTimesheetDate"]);
            $assignment->setEndDate($projectData["ProjectEstimatedEndDate"]);
            $assignment->setRate($detail["StandartRate"] > $detail["OnsiteRate"] ? $detail["StandartRate"]:$detail["OnsiteRate"]);
            $assignment->setType(null);
            $assignment->setRole($detail["ProjectStaffRoleTypeName"]);
            $assignment->setStatus($detail["Active"]);
            $assignment->setUtilization(null);
            $assignment->setRelatedEngineer($detail["StaffId"]);
            $assignment->setRelatedProject($detail["ProjectId"]);
            $this->assignmentsList->addAssignment($assignment);

            $this->rateCard[$detail["ProjectStaffRoleTypeName"]] = [
                "Role" => $detail["ProjectStaffRoleTypeName"],
                "Rate" => $assignment->getRate(),
            ];
        }
    }

    private function collectAssignmentForUser($engineerId, $assignmentId): array
    {
        $engineer = $this->engineersList->getEngineer($engineerId);
        if(false !== $engineer){
            $engineer->setRelatedAssignments(array_merge($engineer->getRelatedAssignments(), [$assignmentId]));
            return $engineer->getRelatedAssignments();
        }
        return [$assignmentId => $assignmentId];
    }

    /**
     * @return ProjectsList
     */
    public function getProjectsList(): ProjectsList
    {
        return $this->projectsList;
    }

    /**
     * @param ProjectsList $projectsList
     */
    public function setProjectsList(ProjectsList $projectsList): void
    {
        $this->projectsList = $projectsList;
    }

    /**
     * @return EngineersList
     */
    public function getEngineersList(): EngineersList
    {
        return $this->engineersList;
    }

    /**
     * @param EngineersList $engineersList
     */
    public function setEngineersList(EngineersList $engineersList): void
    {
        $this->engineersList = $engineersList;
    }

    /**
     * @return AssignmentsList
     */
    public function getAssignmentsList(): AssignmentsList
    {
        return $this->assignmentsList;
    }

    /**
     * @param AssignmentsList $assignmentsList
     */
    public function setAssignmentsList(AssignmentsList $assignmentsList): void
    {
        $this->assignmentsList = $assignmentsList;
    }

    /**
     * @return ContractsLists
     */
    public function getContractsLists(): ContractsLists
    {
        return $this->contractsLists;
    }

    /**
     * @param ContractsLists $contractsLists
     */
    public function setContractsLists(ContractsLists $contractsLists): void
    {
        $this->contractsLists = $contractsLists;
    }

    /**
     * @return array
     */
    public function getRateCard(): array
    {
        return $this->rateCard;
    }

}