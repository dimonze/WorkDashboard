<?php

namespace App\Command\Scrapers\DataArtPM\Storages;

use App\Command\Scrapers\DataArtPM\Entities\Project;

class ProjectsList
{
    private array $projects = [];

    public function addProject(Project $project): void
    {
        $this->projects[$project->getId()] = $project;
    }

    public function getProject(int $id): Project
    {
        return $this->projects[$id];
    }

    /**
     * Returns a list of Projects objects
     * @return Project[]
     */
    public function getAllProjects()
    {
        return $this->projects;
    }
}