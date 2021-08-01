<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
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
     * @ORM\ManyToMany(targetEntity=SubjectYear::class, mappedBy="addclass")
     */
    private $assignclass;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="rclass")
     */
    private $deptclass;

    public function __construct()
    {
        $this->assignclass = new ArrayCollection();
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

    /**
     * @return Collection|SubjectYear[]
     */
    public function getAssignclass(): Collection
    {
        return $this->assignclass;
    }

    public function addAssignclass(SubjectYear $assignclass): self
    {
        if (!$this->assignclass->contains($assignclass)) {
            $this->assignclass[] = $assignclass;
            $assignclass->addAddclass($this);
        }

        return $this;
    }

    public function removeAssignclass(SubjectYear $assignclass): self
    {
        if ($this->assignclass->removeElement($assignclass)) {
            $assignclass->removeAddclass($this);
        }

        return $this;
    }

    public function getDeptclass(): ?Department
    {
        return $this->deptclass;
    }

    public function setDeptclass(?Department $deptclass): self
    {
        $this->deptclass = $deptclass;

        return $this;
    }
}
