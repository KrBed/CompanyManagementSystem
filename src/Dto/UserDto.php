<?php


namespace App\Dto;


class UserDto
{
    private $id;
    private $firstName;
    private $lastName;
    private $shifts;
    private $department;
    private $fullName;

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department): void
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->getFirstName() .' '.$this->getLastName();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return array
     */
    public function getShifts(): array
    {
        return $this->shifts;
    }

    /**
     * @param array $shifts
     */
    public function setShifts(array $shifts): void
    {
        $this->shifts = $shifts;
    }
    public function __construct()
    {
        $this->shifts = array();
    }
}