<?php

namespace App\Entity;

use App\Repository\ContractsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractsRepository::class)]
class Contracts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'relatedContract', targetEntity: Projects::class, cascade: ['persist', 'remove'])]
    private Collection $relatedProjects;

    #[ORM\Column(unique: true, nullable: true)]
    private ?float $contractID = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $signedDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $budgetCap = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $owner = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $budgetOwner = null;

    #[ORM\Column(nullable: true)]
    private ?float $fte = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $assignProjects = null;

    #[ORM\OneToMany(mappedBy: 'relatedContract', targetEntity: AssignedRoles::class, cascade: ['persist', 'remove'])]
    private Collection $assignedRoles;

    #[ORM\Column(nullable: true)]
    private ?float $advancedPayment = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isSentToLegal = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isSentToDavid = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isSentToCfa = null;

    #[ORM\Column(nullable: true)]
    private ?int $legalId = null;


    public function __construct()
    {
        $this->relatedProjects = new ArrayCollection();
        $this->assignedRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Projects>
     */
    public function getRelatedProjects(): Collection
    {
        return $this->relatedProjects;
    }

    public function addRelatedProject(Projects $relatedProject): self
    {
        if (!$this->relatedProjects->contains($relatedProject)) {
            $this->relatedProjects->add($relatedProject);
            $relatedProject->setRelatedContract($this);
        }

        return $this;
    }

    public function removeRelatedProject(Projects $relatedProject): self
    {
        if ($this->relatedProjects->removeElement($relatedProject)) {
            // set the owning side to null (unless already changed)
            if ($relatedProject->getRelatedContract() === $this) {
                $relatedProject->setRelatedContract(null);
            }
        }

        return $this;
    }

    public function getContractID(): ?float
    {
        return $this->contractID;
    }

    public function setContractID(?float $contractID): self
    {
        $this->contractID = $contractID;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSignedDate(): ?\DateTimeInterface
    {
        return $this->signedDate;
    }

    public function setSignedDate(?\DateTimeInterface $signedDate): self
    {
        $this->signedDate = $signedDate;

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

    public function getBudgetCap(): ?float
    {
        return $this->budgetCap;
    }

    public function setBudgetCap(?float $budgetCap): self
    {
        $this->budgetCap = $budgetCap;

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

    public function getBudgetOwner(): ?string
    {
        return $this->budgetOwner;
    }

    public function setBudgetOwner(?string $budgetOwner): self
    {
        $this->budgetOwner = $budgetOwner;

        return $this;
    }

    public function getFte(): ?float
    {
        return $this->fte;
    }

    public function setFte(?float $fte): self
    {
        $this->fte = $fte;

        return $this;
    }

    public function getAssignProjects(): ?int
    {
        return $this->assignProjects;
    }

    public function setAssignProjects(?int $assignProjects): self
    {
        $this->assignProjects = $assignProjects;

        return $this;
    }

    /**
     * @return Collection<int, AssignedRoles>
     */
    public function getAssignedRoles(): Collection
    {
        return $this->assignedRoles;
    }

    public function addAssignedRole(AssignedRoles $assignedRole): self
    {
        if (!$this->assignedRoles->contains($assignedRole)) {
            $this->assignedRoles->add($assignedRole);
            $assignedRole->setRelatedContract($this);
        }

        return $this;
    }

    public function removeAssignedRole(AssignedRoles $assignedRole): self
    {
        if ($this->assignedRoles->removeElement($assignedRole)) {
            // set the owning side to null (unless already changed)
            if ($assignedRole->getRelatedContract() === $this) {
                $assignedRole->setRelatedContract(null);
            }
        }

        return $this;
    }

    public function getAdvancedPayment(): ?float
    {
        return $this->advancedPayment;
    }

    public function setAdvancedPayment(?float $advancedPayment): self
    {
        $this->advancedPayment = $advancedPayment;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function isIsSentToLegal(): ?bool
    {
        return $this->isSentToLegal;
    }

    public function setIsSentToLegal(bool $isSentToLegal): self
    {
        $this->isSentToLegal = $isSentToLegal;

        return $this;
    }

    public function isIsSentToDavid(): ?bool
    {
        return $this->isSentToDavid;
    }

    public function setIsSentToDavid(bool $isSentToDavid): self
    {
        $this->isSentToDavid = $isSentToDavid;

        return $this;
    }

    public function isIsSentToCfa(): ?bool
    {
        return $this->isSentToCfa;
    }

    public function setIsSentToCfa(bool $isSentToCfa): self
    {
        $this->isSentToCfa = $isSentToCfa;

        return $this;
    }

    public function getLegalId(): ?int
    {
        return $this->legalId;
    }

    public function setLegalId(?int $legalId): self
    {
        $this->legalId = $legalId;

        return $this;
    }

}
