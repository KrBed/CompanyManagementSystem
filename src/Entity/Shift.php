<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=255)
     */
    private $layout;

    /**
     * @ORM\Column(type="time")
     */
    private $startTime;

    /**
     * @ORM\Column(type="time")
     */
    private $endTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfHours;

    /**
     * @ORM\Column(type="smallint")
     */
    private $countTimeBeforeWork;

    /**
     * @ORM\Column(type="smallint")
     */
    private $countTimeAfterWork;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="smallint")
     */
    private $overrideExistingDutyRoosters;

    /**
     * @ORM\Column(type="smallint")
     */
    private $avoidChristmas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DutyRooster", inversedBy="shifts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dutyRooster;

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

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function setLayout(string $layout): self
    {
        $this->layout = $layout;

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

    public function getNumberOfHours(): ?int
    {
        return $this->numberOfHours;
    }

    public function setNumberOfHours(int $numberOfHours): self
    {
        $this->numberOfHours = $numberOfHours;

        return $this;
    }

    public function getCountTimeBeforeWork(): ?int
    {
        return $this->countTimeBeforeWork;
    }

    public function setCountTimeBeforeWork(int $countTimeBeforeWork): self
    {
        $this->countTimeBeforeWork = $countTimeBeforeWork;

        return $this;
    }

    public function getCountTimeAfterWork(): ?int
    {
        return $this->countTimeAfterWork;
    }

    public function setCountTimeAfterWork(int $countTimeAfterWork): self
    {
        $this->countTimeAfterWork = $countTimeAfterWork;

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

    public function getOverrideExistingDutyRoosters(): ?int
    {
        return $this->overrideExistingDutyRoosters;
    }

    public function setOverrideExistingDutyRoosters(int $overrideExistingDutyRoosters): self
    {
        $this->overrideExistingDutyRoosters = $overrideExistingDutyRoosters;

        return $this;
    }

    public function getAvoidChristmas(): ?int
    {
        return $this->avoidChristmas;
    }

    public function setAvoidChristmas(int $avoidChristmas): self
    {
        $this->avoidChristmas = $avoidChristmas;

        return $this;
    }

    public function getDutyRooster(): ?DutyRooster
    {
        return $this->dutyRooster;
    }

    public function setDutyRooster(?DutyRooster $dutyRooster): self
    {
        $this->dutyRooster = $dutyRooster;

        return $this;
    }
}
