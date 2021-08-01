<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
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
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="addDepartment")
     */
    private $assignUser;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="sdept")
     */
    private $departmentadded;

    /**
     * @ORM\OneToMany(targetEntity=Course::class, mappedBy="deptclass")
     */
    private $rclass;

    public function __construct()
    {
        $this->assignUser = new ArrayCollection();
        $this->departmentadded = new ArrayCollection();
        $this->rclass = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getAssignUser(): Collection
    {
        return $this->assignUser;
    }

    public function addAssignUser(User $assignUser): self
    {
        if (!$this->assignUser->contains($assignUser)) {
            $this->assignUser[] = $assignUser;
            $assignUser->setAddDepartment($this);
        }

        return $this;
    }

    public function removeAssignUser(User $assignUser): self
    {
        if ($this->assignUser->removeElement($assignUser)) {
            // set the owning side to null (unless already changed)
            if ($assignUser->getAddDepartment() === $this) {
                $assignUser->setAddDepartment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getDepartmentadded(): Collection
    {
        return $this->departmentadded;
    }

    public function addDepartmentadded(Student $departmentadded): self
    {
        if (!$this->departmentadded->contains($departmentadded)) {
            $this->departmentadded[] = $departmentadded;
            $departmentadded->setSdept($this);
        }

        return $this;
    }

    public function removeDepartmentadded(Student $departmentadded): self
    {
        if ($this->departmentadded->removeElement($departmentadded)) {
            // set the owning side to null (unless already changed)
            if ($departmentadded->getSdept() === $this) {
                $departmentadded->setSdept(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getRclass(): Collection
    {
        return $this->rclass;
    }

    public function addRclass(Course $rclass): self
    {
        if (!$this->rclass->contains($rclass)) {
            $this->rclass[] = $rclass;
            $rclass->setDeptclass($this);
        }

        return $this;
    }

    public function removeRclass(Course $rclass): self
    {
        if ($this->rclass->removeElement($rclass)) {
            // set the owning side to null (unless already changed)
            if ($rclass->getDeptclass() === $this) {
                $rclass->setDeptclass(null);
            }
        }

        return $this;
    }
}
