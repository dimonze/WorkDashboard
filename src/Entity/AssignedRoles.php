<?php

namespace App\Entity;

use App\Repository\AssignedRolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssignedRolesRepository::class)]
class AssignedRoles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Roles $role = null;

    #[ORM\ManyToOne(inversedBy: 'assignedRoles')]
    private ?Contracts $relatedContract = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $utilization = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?Roles
    {
        return $this->role;
    }

    public function setRole(?Roles $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRelatedContract(): ?Contracts
    {
        return $this->relatedContract;
    }

    public function setRelatedContract(?Contracts $relatedContract): self
    {
        $this->relatedContract = $relatedContract;

        return $this;
    }

    public function __toString(): string
    {
        return $this->role->getName();
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getUtilization(): ?float
    {
        return $this->utilization;
    }

    public function setUtilization(?float $utilization): self
    {
        $this->utilization = $utilization;

        return $this;
    }

}
