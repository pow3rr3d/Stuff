<?php

namespace App\Entity;

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
}
