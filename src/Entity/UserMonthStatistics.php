<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserMonthStatisticsRepository")
 */
class UserMonthStatistics
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()make
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $period;

    /**
     * @ORM\Column(type="float")
     */
    private $hoursWorked;

    /**
     * @ORM\Column(type="float")
     */
    private $overtimeHours;

    /**
     * @ORM\Column(type="integer")
     */
    private $dayPresence;

    /**
     * @ORM\Column(type="integer")
     */
    private $lateness;

    /**
     * @ORM\Column(type="integer")
     */
    private $absence;

    /**
     * @ORM\Column(type="float")
     */
    private $monthSalary;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getHoursWorked(): ?float
    {
        return $this->hoursWorked;
    }

    public function setHoursWorked(float $hoursWorked): self
    {
        $this->hoursWorked = $hoursWorked;

        return $this;
    }

    public function getOvertimeHours(): ?float
    {
        return $this->overtimeHours;
    }

    public function setOvertimeHours(float $overtimeHours): self
    {
        $this->overtimeHours = $overtimeHours;

        return $this;
    }

    public function getDayPresence(): ?int
    {
        return $this->dayPresence;
    }

    public function setDayPresence(int $dayPresence): self
    {
        $this->dayPresence = $dayPresence;

        return $this;
    }

    public function getLateness(): ?int
    {
        return $this->lateness;
    }

    public function setLateness(int $lateness): self
    {
        $this->lateness = $lateness;

        return $this;
    }

    public function getAbsence(): ?int
    {
        return $this->absence;
    }

    public function setAbsence(int $absence): self
    {
        $this->absence = $absence;

        return $this;
    }

    public function getMonthSalary(): ?float
    {
        return $this->monthSalary;
    }

    public function setMonthSalary(float $monthSalary): self
    {
        $this->monthSalary = $monthSalary;

        return $this;
    }
}
