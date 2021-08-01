<?php

namespace App\Entity;

use App\Repository\SubjectYearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubjectYearRepository::class)
 */
class SubjectYear
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
    private $semester;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $syear;

    /**
     * @ORM\ManyToMany(targetEntity=Result::class, mappedBy="SubjectResult")
     */
    private $addsubjectR;

    /**
     * @ORM\ManyToMany(targetEntity=Student::class, mappedBy="subjectTaken")
     */
    private $addStudentSubject;

    /**
     * @ORM\ManyToOne(targetEntity=Subject::class, inversedBy="setyear")
     */
    private $subjectname;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="subjy")
     */
    private $steacher;

    /**
     * @ORM\ManyToMany(targetEntity=Course::class, inversedBy="assignclass")
     */
    private $addclass;



    public function __construct()
    {
        $this->addsubjectR = new ArrayCollection();
        $this->addStudentSubject = new ArrayCollection();
        $this->addclass = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSemester(): ?string
    {
        return $this->semester;
    }

    public function setSemester(string $semester): self
    {
        $this->semester = $semester;

        return $this;
    }

    public function getSyear(): ?string
    {
        return $this->syear;
    }

    public function setSyear(string $syear): self
    {
        $this->syear = $syear;

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getAddsubjectR(): Collection
    {
        return $this->addsubjectR;
    }

    public function addAddsubjectR(Result $addsubjectR): self
    {
        if (!$this->addsubjectR->contains($addsubjectR)) {
            $this->addsubjectR[] = $addsubjectR;
            $addsubjectR->addSubjectResult($this);
        }

        return $this;
    }

    public function removeAddsubjectR(Result $addsubjectR): self
    {
        if ($this->addsubjectR->removeElement($addsubjectR)) {
            $addsubjectR->removeSubjectResult($this);
        }

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getAddStudentSubject(): Collection
    {
        return $this->addStudentSubject;
    }

    public function addAddStudentSubject(Student $addStudentSubject): self
    {
        if (!$this->addStudentSubject->contains($addStudentSubject)) {
            $this->addStudentSubject[] = $addStudentSubject;
            $addStudentSubject->addSubjectTaken($this);
        }

        return $this;
    }

    public function removeAddStudentSubject(Student $addStudentSubject): self
    {
        if ($this->addStudentSubject->removeElement($addStudentSubject)) {
            $addStudentSubject->removeSubjectTaken($this);
        }

        return $this;
    }

    public function getSubjectname(): ?Subject
    {
        return $this->subjectname;
    }

    public function setSubjectname(?Subject $subjectname): self
    {
        $this->subjectname = $subjectname;

        return $this;
    }

    public function getSteacher(): ?User
    {
        return $this->steacher;
    }

    public function setSteacher(?User $steacher): self
    {
        $this->steacher = $steacher;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getAddclass(): Collection
    {
        return $this->addclass;
    }

    public function addAddclass(Course $addclass): self
    {
        if (!$this->addclass->contains($addclass)) {
            $this->addclass[] = $addclass;
        }

        return $this;
    }

    public function removeAddclass(Course $addclass): self
    {
        $this->addclass->removeElement($addclass);

        return $this;
    }


}
