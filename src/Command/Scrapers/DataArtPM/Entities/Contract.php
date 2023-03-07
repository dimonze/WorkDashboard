<?php

namespace App\Command\Scrapers\DataArtPM\Entities;

use App\Command\Helpers\Parsers\GeneralParser;

class Contract
{
    private int $id;
    private Project $relatedProjects;
    private $name;
    private $description;
    private $type;
    private $status;
    private $signedDate;
    private $startDate;
    private $endDate;
    private $budgetCap;
    private $roles;
    private $owner;
    private $budgetOwner;

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
     * @return Project
     */
    public function getRelatedProjects(): Project
    {
        return $this->relatedProjects;
    }

    /**
     * @param Project $relatedProjects
     */
    public function setRelatedProjects(Project $relatedProjects): void
    {
        $this->relatedProjects = $relatedProjects;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
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
    public function getSignedDate()
    {
        return $this->signedDate;
    }

    /**
     * @param mixed $signedDate
     */
    public function setSignedDate($signedDate): void
    {
        $this->signedDate = $signedDate;
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
    public function getBudgetCap()
    {
        return $this->budgetCap;
    }

    /**
     * @param mixed $budgetCap
     */
    public function setBudgetCap($budgetCap): void
    {
        $this->budgetCap = $budgetCap;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
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
    public function getBudgetOwner()
    {
        return $this->budgetOwner;
    }

    /**
     * @param mixed $budgetOwner
     */
    public function setBudgetOwner($budgetOwner): void
    {
        $this->budgetOwner = $budgetOwner;
    }
}