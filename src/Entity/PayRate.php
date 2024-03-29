<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PayRatesRepository")
 */
class PayRate
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $obtainFrom;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\Positive
     */
    private $ratePerHour;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     *  @Assert\Positive
     */
    private $overtimeRate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="payRates")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObtainFrom(): ?\DateTimeInterface
    {
        return $this->obtainFrom;
    }

    public function setObtainFrom(\DateTimeInterface $obtainFrom): self
    {
        $this->obtainFrom = $obtainFrom;

        return $this;
    }

    public function getRatePerHour(): ?string
    {
        return $this->ratePerHour;
    }

    public function setRatePerHour(?string $ratePerHour): self
    {
        $this->ratePerHour = $ratePerHour;

        return $this;
    }

    public function getOvertimeRate(): ?string
    {
        return $this->overtimeRate;
    }

    public function setOvertimeRate(?string $overtimeRate): self
    {
        $this->overtimeRate = $overtimeRate;

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
}
