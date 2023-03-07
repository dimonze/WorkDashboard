<?php

namespace App\Command\Scrapers\DataArtPM\Storages;

use App\Command\Scrapers\DataArtPM\Entities\Assignment;

class AssignmentsList
{
    private array $assignment = [];

    public function addAssignment(Assignment $assignment): void
    {
        $this->assignment[$assignment->getId()] = $assignment;
    }

    public function getAssignment(int $id): Assignment
    {
        return $this->assignment[$id];
    }

    /**
     * Returns a list of Assignment objects
     * @return Assignment[]
     */
    public function getAllAssignments()
    {
        return $this->assignment;
    }

}