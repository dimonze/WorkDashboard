<?php

namespace App\Entity;

use App\Repository\ProjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectsRepository::class)]
class Projects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $projectID = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clientName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $owner = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $projectGroup = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'relatedProjects')]
    private ?Contracts $relatedContract = null;

    #[ORM\OneToMany(mappedBy: 'relatedProject', targetEntity: Assignments::class, cascade: ['persist', 'remove'])]
    private Collection $relatedAssignments;

    public function __construct()
    {
        $this->relatedAssignments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectID(): ?int
    {
        return $this->projectID;
    }

    public function setProjectID(?int $projectID): self
    {
        $this->projectID = $projectID;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(?string $clientName): self
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(?string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProjectGroup(): ?string
    {
        return $this->projectGroup;
    }

    public function setProjectGroup(?string $projectGroup): self
    {
        $this->projectGroup = $projectGroup;

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

    public function getRelatedContract(): ?Contracts
    {
        return $this->relatedContract;
    }

    public function setRelatedContract(?Contracts $relatedContract): self
    {
        $this->relatedContract = $relatedContract;

        return $this;
    }

    /**
     * @return Collection<int, Assignments>
     */
    public function getRelatedAssignments(): Collection
    {
        return $this->relatedAssignments;
    }

    public function addRelatedAssigment(Assignments $relatedAssigment): self
    {
        if (!$this->relatedAssignments->contains($relatedAssigment)) {
            $this->relatedAssignments->add($relatedAssigment);
            $relatedAssigment->setRelatedProject($this);
        }

        return $this;
    }

    public function removeRelatedAssigment(Assignments $relatedAssigment): self
    {
        if ($this->relatedAssignments->removeElement($relatedAssigment)) {
            // set the owning side to null (unless already changed)
            if ($relatedAssigment->getRelatedProject() === $this) {
                $relatedAssigment->setRelatedProject(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
