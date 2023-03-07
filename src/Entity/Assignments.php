<?php

namespace App\Entity;

use App\Repository\AssignmentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssignmentsRepository::class)]
class Assignments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'relatedAssignments')]
    private ?Projects $relatedProject = null;

    #[ORM\ManyToOne(inversedBy: 'relatedAssignments')]
    private ?Engineers $relatedEngineer = null;

    #[ORM\Column(nullable: true)]
    private ?int $assignmentID = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $role = null;

    #[ORM\Column(nullable: true)]
    private ?float $rate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $utilization = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelatedProject(): ?Projects
    {
        return $this->relatedProject;
    }

    public function setRelatedProject(?Projects $relatedProject): self
    {
        $this->relatedProject = $relatedProject;

        return $this;
    }

    public function getRelatedEngineer(): ?Engineers
    {
        return $this->relatedEngineer;
    }

    public function setRelatedEngineer(?Engineers $relatedEngineer): self
    {
        $this->relatedEngineer = $relatedEngineer;

        return $this;
    }

    public function getAssignmentID(): ?int
    {
        return $this->assignmentID;
    }

    public function setAssignmentID(?int $assignmentID): self
    {
        $this->assignmentID = $assignmentID;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
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
