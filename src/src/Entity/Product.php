<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity=Subcategory::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subcategory;

    /**
     * @ORM\ManyToOne(targetEntity=Loan::class, inversedBy="product")
     */
    private $loan;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=LoanArchive::class, mappedBy="product")
     */
    private $loanArchives;

    private $previousState;

    private $isNormalWear;

    public function __construct()
    {
        $this->loanArchives = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getLoan(): ?Loan
    {
        return $this->loan;
    }

    public function setLoan(?Loan $loan): self
    {
        $this->loan = $loan;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|LoanArchive[]
     */
    public function getLoanArchives(): Collection
    {
        return $this->loanArchives;
    }

    public function addLoanArchive(LoanArchive $loanArchive): self
    {
        if (!$this->loanArchives->contains($loanArchive)) {
            $this->loanArchives[] = $loanArchive;
            $loanArchive->addProduct($this);
        }

        return $this;
    }

    public function removeLoanArchive(LoanArchive $loanArchive): self
    {
        if ($this->loanArchives->removeElement($loanArchive)) {
            $loanArchive->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreviousState()
    {
        return $this->previousState;
    }

    /**
     * @param mixed $previousState
     */
    public function setPreviousState($previousState): void
    {
        $this->previousState = $previousState;
    }

    /**
     * @return mixed
     */
    public function getIsNormalWear()
    {
        return $this->isNormalWear;
    }

    /**
     * @param mixed $isNormalWear
     */
    public function setIsNormalWear($isNormalWear): void
    {
        $this->isNormalWear = $isNormalWear;
    }
}

