<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DutyRoosterRepository")
 */
class DutyRooster
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="dutyRoosters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Shift", mappedBy="dutyRooster")
     */
    private $shifts;

    public function __construct()
    {
        $this->shifts = new ArrayCollection();
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Shift[]
     */
    public function getShifts(): Collection
    {
        return $this->shifts;
    }

    public function addShift(Shift $shift): self
    {
        if (!$this->shifts->contains($shift)) {
            $this->shifts[] = $shift;
            $shift->setDutyRooster($this);
        }

        return $this;
    }

    public function removeShift(Shift $shift): self
    {
        if ($this->shifts->contains($shift)) {
            $this->shifts->removeElement($shift);
            // set the owning side to null (unless already changed)
            if ($shift->getDutyRooster() === $this) {
                $shift->setDutyRooster(null);
            }
        }

        return $this;
    }
}
