<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $martalstatus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nationality;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phoneno;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ResetToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userimage;

    /**
     * @ORM\OneToMany(targetEntity=SubjectYear::class, mappedBy="steacher")
     */
    private $subjy;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="assignUser")
     */
    private $addDepartment;

    public function __construct()
    {
        $this->subjy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

   /** Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */

    public function getRoles()
    {
      //  $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
       // $roles[] = 'ROLE_USER';

        return $this->roles;
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getMartalstatus(): ?string
    {
        return $this->martalstatus;
    }

    public function setMartalstatus(string $martalstatus): self
    {
        $this->martalstatus = $martalstatus;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getPhoneno(): ?int
    {
        return $this->phoneno;
    }

    public function setPhoneno(?int $phoneno): self
    {
        $this->phoneno = $phoneno;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->ResetToken;
    }

    public function setResetToken(?string $ResetToken): self
    {
        $this->ResetToken = $ResetToken;

        return $this;
    }

    public function getUserimage(): ?string
    {
        return $this->userimage;
    }

    public function setUserimage(?string $userimage): self
    {
        $this->userimage = $userimage;

        return $this;
    }

    /**
     * @return Collection|SubjectYear[]
     */
    public function getSubjy(): Collection
    {
        return $this->subjy;
    }

    public function addSubjy(?SubjectYear $subjy): self
    {
        if (!$this->subjy->contains($subjy)) {
            $this->subjy[] = $subjy;
            $subjy->setSteacher($this);
        }

        return $this;
    }

    public function removeSubjy(?SubjectYear $subjy): self
    {
        if ($this->subjy->removeElement($subjy)) {
            // set the owning side to null (unless already changed)
            if ($subjy->getSteacher() === $this) {
                $subjy->setSteacher(null);
            }
        }

        return $this;
    }

    public function getAddDepartment(): ?Department
    {
        return $this->addDepartment;
    }

    public function setAddDepartment(?Department $addDepartment): self
    {
        $this->addDepartment = $addDepartment;

        return $this;
    }
}
