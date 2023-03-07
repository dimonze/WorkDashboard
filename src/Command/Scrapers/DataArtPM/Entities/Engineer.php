<?php

namespace App\Command\Scrapers\DataArtPM\Entities;

class Engineer
{
    private int $id;
    private $name;
    private $location;
    private array $relatedAssignments = [];
    private $roles;
    private $defaultRole;
    private $selfRate;

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
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
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
    public function getDefaultRole()
    {
        return $this->defaultRole;
    }

    /**
     * @param mixed $defaultRole
     */
    public function setDefaultRole($defaultRole): void
    {
        $this->defaultRole = $defaultRole;
    }

    /**
     * @return mixed
     */
    public function getSelfRate()
    {
        return $this->selfRate;
    }

    /**
     * @param mixed $selfRate
     */
    public function setSelfRate($selfRate): void
    {
        $this->selfRate = $selfRate;
    }
}