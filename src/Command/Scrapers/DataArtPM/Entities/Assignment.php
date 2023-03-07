<?php

namespace App\Command\Scrapers\DataArtPM\Entities;

use App\Command\Helpers\Parsers\GeneralParser;

class Assignment
{
    private int $id;
    private int $relatedProject;
    private int $relatedEngineer;
    private $type;
    private $role;
    private $rate;
    private $status;
    private $startDate;
    private $endDate;
    private $utilization;

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
     * @return int
     */
    public function getRelatedProject(): int
    {
        return $this->relatedProject;
    }

    /**
     * @param int $relatedProject
     */
    public function setRelatedProject(int $relatedProject): void
    {
        $this->relatedProject = $relatedProject;
    }

    /**
     * @return int
     */
    public function getRelatedEngineer(): int
    {
        return $this->relatedEngineer;
    }

    /**
     * @param int $relatedEngineer
     */
    public function setRelatedEngineer(int $relatedEngineer): void
    {
        $this->relatedEngineer = $relatedEngineer;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate): void
    {
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
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
     * @return mixed
     */
    public function getUtilization()
    {
        return $this->utilization;
    }

    /**
     * @param mixed $utilization
     */
    public function setUtilization($utilization): void
    {
        $this->utilization = $utilization;
    }

}