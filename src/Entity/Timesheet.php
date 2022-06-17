<?php


namespace App\Entity;


class Timesheet
{
    private const FIRST_CHANGE = "07:00";
    private const SECOND_CHANGE = "15:00";
    private const THIRD_CHANGE = "22:00";
    private const RANDOM_TAB = [0, 1];

    public static function createRandomTimesheet(\DateTime $dateFrom, \DateTime $dateTo)
    {
        $timesheet = [];
        $timesheet["dateFrom"] = $dateFrom->format('d-m-Y');
        $timesheet["dateTo"] = $dateTo->format('d-m-Y');
        $timesheet["weekDays"] = self::createRandomWeeekDays(5);
        $timesheet["timeFrom"] = self::createTimeFrom();
        $timesheet["timeTo"] = self::createTimeTo($timesheet["timeFrom"]);
        $timesheet["countTimeBeforeWork"] = self::createRandomCoutnTimeBeforeWork();
        $timesheet["countTimeAfterWork"] = self::createRandomCoutnTimeAfterWork();
        $timesheet["overtimeDutyRooster"] = self::createRandomOverTimeDutyRooster();

        return $timesheet;
    }

    public static function createRandomWeeekDays(int $numberOfDays)
    {
        //A numeric representation of the day (0 for Sunday, 6 for Saturday)
        $days = array(0, 1, 2, 3, 4, 5, 6);
        $weekDays = array_rand($days, $numberOfDays);
        return $weekDays;
    }

    public static function createTimeFrom()
    {
        $changes = array(1, 2);

        $result = array_rand($changes, 1);
        $change = $changes[$result];

        if ($change === 1) {
            return self::FIRST_CHANGE;
        }
        if ($change === 2) {
            return self::SECOND_CHANGE;
        }
    }

    public static function createTimeTo($timeFrom)
    {
        $workingHours = self::getRandomWorkingHours();
        $randomMinutes = self::getRandomMinutes();
        $hoursSum = self::convertTimeFromToInt($timeFrom) + $workingHours;

        if ($hoursSum > 24) {
            $timeTo = $hoursSum - 24;
            $timeTo = (string)$timeTo . ":" . $randomMinutes;
        } else {
            $timeTo = $hoursSum . ":" . $randomMinutes;
        }

        return $timeTo;
    }

    private static function getRandomWorkingHours()
    {
        $workingHours = [4, 5, 6, 7, 8, 9, 10];

        return $workingHours[array_rand($workingHours, 1)];
    }

    private static function getRandomMinutes()
    {
        $minutes = ['00', '30'];
        $result = array_rand($minutes, 1);

        return $minutes[$result];
    }

    private static function convertTimeFromToInt($timeFrom)
    {
        $hour = substr($timeFrom, 0, 2);
        return (int)$hour;
    }

    private static function createRandomCoutnTimeBeforeWork()
    {
        $result = array_rand(self::RANDOM_TAB, 1);
        if ($result === 1) {
            return true;
        } else {
            return false;
        }
    }

    private static function createRandomCoutnTimeAfterWork()
    {
        $result = array_rand(self::RANDOM_TAB, 1);
        if ($result === 1) {
            return true;
        } else {
            return false;
        }
    }

    private static function createRandomOverTimeDutyRooster()
    {
        $result = array_rand(self::RANDOM_TAB, 1);
        if ($result === 1) {
            return true;
        } else {
            return false;
        }
    }
}