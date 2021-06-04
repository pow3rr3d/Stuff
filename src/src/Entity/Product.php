<?php

namespace App\Entity;

use App\Kernel;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Vich\Uploadable()
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
     * @ORM\JoinColumn(nullable=true)
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $imageName;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="product", fileNameProperty="imageName")
     * @Assert\File(
     *     mimeTypes = {"image/jpeg", "image/jpeg"},
     *     mimeTypesMessage = "Please upload a JPEG image"
     * )
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

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

    public function setSubcategory(?Subcategory $subcategory): ?self
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

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @param string|null $imageName
     * @return Product
     */
    public function setImageName(?string $imageName): Product
    {
        $this->imageName = $imageName;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Product
     */
    public function setImageFile(?File $imageFile): Product
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }


}