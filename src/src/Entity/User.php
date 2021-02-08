<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Serializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity=Loan::class, mappedBy="loanedBy")
     */
    private $loans;

    /**
     * @ORM\OneToMany(targetEntity=Loan::class, mappedBy="borrowedBy")
     */
    private $borrowing;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="user")
     */
    private $products;

    public function __construct()
    {
        $this->loans = new ArrayCollection();
        $this->borrowing = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        return [$this->roles];
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

// Custom function for UserInterface

    public function eraseCredentials()
    {
    }

      /** @see \Serializable::serialize() */
      public function serialize()
      {
          return serialize(array(
              $this->id,
              $this->name,
              $this->surname, 
              $this->email, 
              $this->password,
              $this->roles
          ));
      }
  
      /** @see \Serializable::unserialize() */
      public function unserialize($serialized)
      {
          list (
            $this->id,
            $this->name,
            $this->surname, 
            $this->email, 
            $this->password,
            $this->roles
          ) = unserialize($serialized, array('allowed_classes' => false));
      }

      public function getSalt()
      {
          // you *may* need a real salt depending on your encoder
          // see section on salt below
          return null;
      }

      public function getUsername()
      {
          return $this->name . ' ' . $this->surname;
      }

      /**
       * @return Collection|Loan[]
       */
      public function getLoans(): Collection
      {
          return $this->loans;
      }

      public function addLoan(Loan $loan): self
      {
          if (!$this->loans->contains($loan)) {
              $this->loans[] = $loan;
              $loan->setLoanedBy($this);
          }

          return $this;
      }

      public function removeLoan(Loan $loan): self
      {
          if ($this->loans->removeElement($loan)) {
              // set the owning side to null (unless already changed)
              if ($loan->getLoanedBy() === $this) {
                  $loan->setLoanedBy(null);
              }
          }

          return $this;
      }

      /**
       * @return Collection|Loan[]
       */
      public function getBorrowing(): Collection
      {
          return $this->borrowing;
      }

      public function addBorrowing(Loan $borrowing): self
      {
          if (!$this->borrowing->contains($borrowing)) {
              $this->borrowing[] = $borrowing;
              $borrowing->setBorrowedBy($this);
          }

          return $this;
      }

      public function removeBorrowing(Loan $borrowing): self
      {
          if ($this->borrowing->removeElement($borrowing)) {
              // set the owning side to null (unless already changed)
              if ($borrowing->getBorrowedBy() === $this) {
                  $borrowing->setBorrowedBy(null);
              }
          }

          return $this;
      }

      /**
       * @return Collection|Product[]
       */
      public function getProducts(): Collection
      {
          return $this->products;
      }

      public function addProduct(Product $product): self
      {
          if (!$this->products->contains($product)) {
              $this->products[] = $product;
              $product->setUser($this);
          }

          return $this;
      }

      public function removeProduct(Product $product): self
      {
          if ($this->products->removeElement($product)) {
              // set the owning side to null (unless already changed)
              if ($product->getUser() === $this) {
                  $product->setUser(null);
              }
          }

          return $this;
      }
}
