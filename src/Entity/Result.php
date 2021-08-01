<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $test1;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $test2;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $individual1;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $individual2;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $groupWork;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $signstatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addstatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addRegister;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ResetToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reported;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="AddResultStudent")
     */
    private $StundentNameR;

    /**
     * @ORM\ManyToMany(targetEntity=SubjectYear::class, inversedBy="addsubjectR")
     */
    private $SubjectResult;

    public function __construct()
    {
        $this->SubjectResult = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTest1(): ?float
    {
        return $this->test1;
    }

    public function setTest1(float $test1): self
    {
        $this->test1 = $test1;

        return $this;
    }

    public function getTest2(): ?float
    {
        return $this->test2;
    }

    public function setTest2(?float $test2): self
    {
        $this->test2 = $test2;

        return $this;
    }

    public function getIndividual1(): ?float
    {
        return $this->individual1;
    }

    public function setIndividual1(?float $individual1): self
    {
        $this->individual1 = $individual1;

        return $this;
    }

    public function getIndividual2(): ?float
    {
        return $this->individual2;
    }

    public function setIndividual2(?float $individual2): self
    {
        $this->individual2 = $individual2;

        return $this;
    }

    public function getGroupWork(): ?float
    {
        return $this->groupWork;
    }

    public function setGroupWork(?float $groupWork): self
    {
        $this->groupWork = $groupWork;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getPercent(): ?float
    {
        return $this->percent;
    }

    public function setPercent(?float $percent): self
    {
        $this->percent = $percent;

        return $this;
    }

    public function getSignstatus(): ?string
    {
        return $this->signstatus;
    }

    public function setSignstatus(?string $signstatus): self
    {
        $this->signstatus = $signstatus;

        return $this;
    }

    public function getAddstatus(): ?string
    {
        return $this->addstatus;
    }

    public function setAddstatus(?string $addstatus): self
    {
        $this->addstatus = $addstatus;

        return $this;
    }

    public function getAddRegister(): ?string
    {
        return $this->addRegister;
    }

    public function setAddRegister(?string $addRegister): self
    {
        $this->addRegister = $addRegister;

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

    public function getReported(): ?string
    {
        return $this->reported;
    }

    public function setReported(?string $reported): self
    {
        $this->reported = $reported;

        return $this;
    }

    public function getStundentNameR(): ?Student
    {
        return $this->StundentNameR;
    }

    public function setStundentNameR(?Student $StundentNameR): self
    {
        $this->StundentNameR = $StundentNameR;

        return $this;
    }

    /**
     * @return Collection|SubjectYear[]
     */
    public function getSubjectResult(): Collection
    {
        return $this->SubjectResult;
    }

    public function addSubjectResult(SubjectYear $subjectResult): self
    {
        if (!$this->SubjectResult->contains($subjectResult)) {
            $this->SubjectResult[] = $subjectResult;
        }

        return $this;
    }

    public function removeSubjectResult(SubjectYear $subjectResult): self
    {
        $this->SubjectResult->removeElement($subjectResult);

        return $this;
    }
}
