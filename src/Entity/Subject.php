<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubjectRepository::class)
 */
class Subject
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
     * @ORM\OneToMany(targetEntity=SubjectYear::class, mappedBy="subjectname")
     */
    private $setyear;

    public function __construct()
    {
        $this->setyear = new ArrayCollection();
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
    public function getSetyear(): Collection
    {
        return $this->setyear;
    }

    public function addSetyear(SubjectYear $setyear): self
    {
        if (!$this->setyear->contains($setyear)) {
            $this->setyear[] = $setyear;
            $setyear->setSubjectname($this);
        }

        return $this;
    }

    public function removeSetyear(SubjectYear $setyear): self
    {
        if ($this->setyear->removeElement($setyear)) {
            // set the owning side to null (unless already changed)
            if ($setyear->getSubjectname() === $this) {
                $setyear->setSubjectname(null);
            }
        }

        return $this;
    }
}
