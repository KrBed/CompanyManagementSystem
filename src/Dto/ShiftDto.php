<?php


namespace App\Dto;


class ShiftDto
{
    private $userId;
    private $date;
    private $timeFrom;
    private $timeTo;
    private $weekDay;
    private $weekDayNumber;
    private $overtimeDutyRooster;
    private $countTimeBeforeWork;
    private $countTimeAfterWork;

    /**
     * @return mixed
     */
    public function getOvertimeDutyRooster()
    {
        return $this->overtimeDutyRooster;
    }

    /**
     * @param mixed $overtimeDutyRooster
     */
    public function setOvertimeDutyRooster($overtimeDutyRooster): void
    {
        $this->overtimeDutyRooster = $overtimeDutyRooster;
    }

    /**
     * @return mixed
     */
    public function getCountTimeBeforeWork()
    {
        return $this->countTimeBeforeWork;
    }

    /**
     * @param mixed $countTimeBeforeWork
     */
    public function setCountTimeBeforeWork($countTimeBeforeWork): void
    {
        $this->countTimeBeforeWork = $countTimeBeforeWork;
    }

    /**
     * @return boolean
     */
    public function getCountTimeAfterWork()
    {
        return $this->countTimeAfterWork;
    }

    /**
     * @param boolean $countTimeAfterWork
     */
    public function setCountTimeAfterWork($countTimeAfterWork): void
    {
        $this->countTimeAfterWork = $countTimeAfterWork;
    }

    /**
     * @return integer
     */
    public function getNumberOfHours()
    {
        return $this->numberOfHours;
    }

    /**
     * @param mixed integer
     */
    public function setNumberOfHours($numberOfHours): void
    {
        $this->numberOfHours = $numberOfHours;
    }
    private $numberOfHours;

    /**
     * @return mixed
     */
    public function getWeekDayNumber()
    {
        return $this->weekDayNumber;
    }

    /**
     * @param mixed $weekDayNumber
     */
    public function setWeekDayNumber($weekDayNumber): void
    {
        $this->weekDayNumber = $weekDayNumber;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }


    /**
     * @return mixed
     */
    public function getTimeFrom()
    {
        return $this->timeFrom;
    }

    /**
     * @param mixed $timeFrom
     */
    public function setTimeFrom($timeFrom): void
    {
        $this->timeFrom = $timeFrom;
    }

    /**
     * @return mixed
     */
    public function getTimeTo()
    {
        return $this->timeTo;
    }

    /**
     * @param mixed $timeTo
     */
    public function setTimeTo($timeTo): void
    {
        $this->timeTo = $timeTo;
    }

    /**
     * @return mixed
     */
    public function getWeekDay()
    {
        return $this->weekDay;
    }

    /**
     * @param mixed $weekDay
     */
    public function setWeekDay($weekDay): void
    {
        $this->weekDay = $weekDay;
    }


}