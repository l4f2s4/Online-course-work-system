<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $regno;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phoneno;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $YoS;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nationality;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="StundentNameR")
     */
    private $AddResultStudent;

    /**
     * @ORM\ManyToMany(targetEntity=SubjectYear::class, inversedBy="addStudentSubject")
     */
    private $subjectTaken;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="departmentadded")
     */
    private $sdept;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class)
     */
    private $addStudentClass;

    public function __construct()
    {
        $this->AddResultStudent = new ArrayCollection();
        $this->subjectTaken = new ArrayCollection();
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

    public function getRegno(): ?string
    {
        return $this->regno;
    }

    public function setRegno(string $regno): self
    {
        $this->regno = $regno;

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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

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

    public function getYoS(): ?string
    {
        return $this->YoS;
    }

    public function setYoS(string $YoS): self
    {
        $this->YoS = $YoS;

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

    /**
     * @return Collection|Result[]
     */
    public function getAddResultStudent(): Collection
    {
        return $this->AddResultStudent;
    }

    public function addAddResultStudent(?Result $addResultStudent): self
    {
        if (!$this->AddResultStudent->contains($addResultStudent)) {
            $this->AddResultStudent[] = $addResultStudent;
            $addResultStudent->setStundentNameR($this);
        }

        return $this;
    }

    public function removeAddResultStudent(Result $addResultStudent): self
    {
        if ($this->AddResultStudent->removeElement($addResultStudent)) {
            // set the owning side to null (unless already changed)
            if ($addResultStudent->getStundentNameR() === $this) {
                $addResultStudent->setStundentNameR(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SubjectYear[]
     */
    public function getSubjectTaken(): Collection
    {
        return $this->subjectTaken;
    }

    public function addSubjectTaken(SubjectYear $subjectTaken): self
    {
        if (!$this->subjectTaken->contains($subjectTaken)) {
            $this->subjectTaken[] = $subjectTaken;
        }

        return $this;
    }

    public function removeSubjectTaken(?SubjectYear $subjectTaken): self
    {
        $this->subjectTaken->removeElement($subjectTaken);

        return $this;
    }

    public function getSdept(): ?Department
    {
        return $this->sdept;
    }

    public function setSdept(?Department $sdept): self
    {
        $this->sdept = $sdept;

        return $this;
    }

    public function getAddStudentClass(): ?Course
    {
        return $this->addStudentClass;
    }

    public function setAddStudentClass(?Course $addStudentClass): self
    {
        $this->addStudentClass = $addStudentClass;

        return $this;
    }
}
