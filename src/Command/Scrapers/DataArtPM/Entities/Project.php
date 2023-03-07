<?php

namespace App\Command\Scrapers\DataArtPM\Entities;

use App\Command\Helpers\Parsers\GeneralParser;
class Project
{
    private int $id;
    private $clientName;
    private $owner;
    private $name;
    private $projectGroup;
    private $startDate;
    private $endDate;
    private float $relatedContract;
    private array $relatedAssignments;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * @param mixed $clientName
     */
    public function setClientName($clientName): void
    {
        $this->clientName = $clientName;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getProjectGroup()
    {
        return $this->projectGroup;
    }

    /**
     * @param mixed $projectGroup
     */
    public function setProjectGroup($projectGroup): void
    {
        $this->projectGroup = $projectGroup;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = GeneralParser::strToDateObj($startDate);
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = GeneralParser::strToDateObj($endDate);
    }

    /**
     * @return float
     */
    public function getRelatedContract(): float
    {
        return $this->relatedContract;
    }

    /**
     * @param string $relatedContract
     */
    public function setRelatedContract(string $relatedContract): void
    {
        preg_match("/([0-9\.]+)/m", $relatedContract, $matches);
        if(stristr($matches[0], ".")) $relatedContract = (float)($matches[0]."0");
        else $relatedContract = (float)($matches[0].".0");
        $this->relatedContract = $relatedContract;
    }

    /**
     * @return array
     */
    public function getRelatedAssignments(): array
    {
        return $this->relatedAssignments;
    }

    /**
     * @param array $relatedAssignments
     */
    public function setRelatedAssignments(array $relatedAssignments): void
    {
        $this->relatedAssignments = $relatedAssignments;
    }
}