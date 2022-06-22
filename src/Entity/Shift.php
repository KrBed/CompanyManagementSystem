<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ShiftRepository")
 */
class Shift
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $startTime;

    /**
     * @ORM\Column(type="time",nullable=true)
     */
    private $endTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="shifts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $overtimeDutyRooster;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $countTimeBeforeWork;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $countTimeAfterWork;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $numberOfHours;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkStatus", mappedBy="shift", cascade={"persist"})
     */
    private $workStatuses;

    public function __construct()
    {
        $this->workStatuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }


    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

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

    public function getOvertimeDutyRooster(): ?bool
    {
        return $this->overtimeDutyRooster;
    }

    public function setOvertimeDutyRooster(?bool $overtimeDutyRooster): self
    {
        $this->overtimeDutyRooster = $overtimeDutyRooster;

        return $this;
    }

    public function getCountTimeBeforeWork(): ?bool
    {
        return $this->countTimeBeforeWork;
    }

    public function setCountTimeBeforeWork(?bool $countTimeBeforeWork): self
    {
        $this->countTimeBeforeWork = $countTimeBeforeWork;

        return $this;
    }

    public function getCountTimeAfterWork(): ?bool
    {
        return $this->countTimeAfterWork;
    }

    public function setCountTimeAfterWork(?bool $countTimeAfterWork): self
    {
        $this->countTimeAfterWork = $countTimeAfterWork;

        return $this;
    }

    public function getNumberOfHours(): ?int
    {
        return $this->numberOfHours;
    }

    public function setNumberOfHours(?int $numberOfHours): self
    {
        $this->numberOfHours = $numberOfHours;

        return $this;
    }

    /**
     * @return Collection|WorkStatus[]
     */
    public function getWorkStatuses(): Collection
    {
        return $this->workStatuses;
    }

    public function addWorkStatus(WorkStatus $workStatus): self
    {
        if (!$this->workStatuses->contains($workStatus)) {
            $this->workStatuses[] = $workStatus;
            $workStatus->setShift($this);
        }

        return $this;
    }

    public function removeWorkStatus(WorkStatus $workStatus): self
    {
        if ($this->workStatuses->contains($workStatus)) {
            $this->workStatuses->removeElement($workStatus);
            // set the owning side to null (unless already changed)
            if ($workStatus->getShift() === $this) {
                $workStatus->setShift(null);
            }
        }

        return $this;
    }
}
