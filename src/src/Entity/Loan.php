<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoanRepository::class)
 */
class Loan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="loan")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $loanedBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="borrowing")
     * @ORM\JoinColumn(nullable=false)
     */
    private $borrowedBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $loanedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $returnAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity=LoanArchive::class, mappedBy="loan", cascade={"persist", "remove"})
     */
    private $loanArchive;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setLoan($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getLoan() === $this) {
                $product->setLoan(null);
            }
        }

        return $this;
    }

    public function getLoanedBy(): ?User
    {
        return $this->loanedBy;
    }

    public function setLoanedBy(?User $loanedBy): self
    {
        $this->loanedBy = $loanedBy;

        return $this;
    }

    public function getBorrowedBy(): ?User
    {
        return $this->borrowedBy;
    }

    public function setBorrowedBy(?User $borrowedBy): self
    {
        $this->borrowedBy = $borrowedBy;

        return $this;
    }

    public function getLoanedAt(): ?\DateTimeInterface
    {
        return $this->loanedAt;
    }

    public function setLoanedAt(\DateTimeInterface $loanedAt): self
    {
        $this->loanedAt = $loanedAt;

        return $this;
    }

    public function getReturnAt(): ?\DateTimeInterface
    {
        return $this->returnAt;
    }

    public function setReturnAt(?\DateTimeInterface $returnAt): self
    {
        $this->returnAt = $returnAt;

        return $this;
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLoanArchive(): ?LoanArchive
    {
        return $this->loanArchive;
    }

    public function setLoanArchive(LoanArchive $loanArchive): self
    {
        // set the owning side of the relation if necessary
        if ($loanArchive->getLoan() !== $this) {
            $loanArchive->setLoan($this);
        }

        $this->loanArchive = $loanArchive;

        return $this;
    }
}
