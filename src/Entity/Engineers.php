<?php

namespace App\Entity;

use App\Repository\EngineersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EngineersRepository::class)]
class Engineers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $userID = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\OneToMany(mappedBy: 'relatedEngineer', targetEntity: Assignments::class)]
    private Collection $relatedAssignments;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $defaultRole = null;

    #[ORM\Column(nullable: true)]
    private ?float $selfRate = null;

    public function __construct()
    {
        $this->relatedAssignments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserID(): ?int
    {
        return $this->userID;
    }

    public function setUserID(?int $userID): self
    {
        $this->userID = $userID;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

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
            $relatedAssigment->setRelatedEngineer($this);
        }

        return $this;
    }

    public function removeRelatedAssigment(Assignments $relatedAssigment): self
    {
        if ($this->relatedAssignments->removeElement($relatedAssigment)) {
            // set the owning side to null (unless already changed)
            if ($relatedAssigment->getRelatedEngineer() === $this) {
                $relatedAssigment->setRelatedEngineer(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getDefaultRole(): ?string
    {
        return $this->defaultRole;
    }

    public function setDefaultRole(?string $defaultRole): self
    {
        $this->defaultRole = $defaultRole;

        return $this;
    }

    public function getSelfRate(): ?float
    {
        return $this->selfRate;
    }

    public function setSelfRate(?float $selfRate): self
    {
        $this->selfRate = $selfRate;

        return $this;
    }

}
