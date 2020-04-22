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