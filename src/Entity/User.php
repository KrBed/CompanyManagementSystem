<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotNull
     */

    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @var string password before hashing
     * @ORM\Column(type="string")
     */
    private $plainPassword;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */

    private $firstName;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $lastName;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postalCode;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $town;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department", inversedBy="users")
     */
    private $department;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PayRate", mappedBy="user",cascade={"persist"})
     */
    private $payRates;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Position", inversedBy="users")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkStatus", mappedBy="user", orphanRemoval=true)
     */
    private $statuses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Shift", mappedBy="user", orphanRemoval=true)
     */
    private $shifts;


    public function __construct()
    {
        $this->payRates = new ArrayCollection();
        $this->statuses = new ArrayCollection();
        $this->dutyRoosters = new ArrayCollection();
        $this->shifts = new ArrayCollection();

    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }


    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        $department = $this->department;
        if (null === $department) {
            $department = new Department();
            $department->setName('Brak działu');
            return $department;
        }
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection|PayRate[]
     */
    public function getPayRates(): Collection
    {
        return $this->payRates;
    }

    public function addPayRate(PayRate $payRate): self
    {
        if (!$this->payRates->contains($payRate)) {
            $this->payRates[] = $payRate;
            $payRate->setUser($this);
        }

        return $this;
    }

    public function removePayRate(PayRate $payRate): self
    {
        if ($this->payRates->contains($payRate)) {
            $this->payRates->removeElement($payRate);
            // set the owning side to null (unless already changed)
            if ($payRate->getUser() === $this) {
                $payRate->setUser(null);
            }
        }

        return $this;
    }

    public function getPosition(): ?Position
    {
       $position =  $this->position;
        if(null === $position){
            $position = new Position();
            $position->setName('Brak stanowiska');
            return $position;
        }

        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getFullName():string
    {
        return $this->getFirstName().' '. $this->getLastName();
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|WorkStatus[]
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    public function addStatus(WorkStatus $status): self
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses[] = $status;
            $status->setUser($this);
        }

        return $this;
    }

    public function removeStatus(WorkStatus $status): self
    {
        if ($this->statuses->contains($status)) {
            $this->statuses->removeElement($status);
            // set the owning side to null (unless already changed)
            if ($status->getUser() === $this) {
                $status->setUser(null);
            }
        }

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
            $shift->setUser($this);
        }

        return $this;
    }

    public function removeShift(Shift $shift): self
    {
        if ($this->shifts->contains($shift)) {
            $this->shifts->removeElement($shift);
            // set the owning side to null (unless already changed)
            if ($shift->getUser() === $this) {
                $shift->setUser(null);
            }
        }

        return $this;
    }

}
