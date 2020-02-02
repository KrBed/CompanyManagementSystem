<?php


namespace App\ViewModels;


use App\Entity\WorkStatus;

class WorkStatusDto
{
    public $sendBy;

    public $time;
    public $date;
    public $day;
    public $name;
    public $userName;
    public $department;

    static function createWorkStatusDto(WorkStatus $workStatus, string $sendBy)
    {
        $dto = new self();
        $dto->name = $workStatus->getStatus();
        $dto->time = $workStatus->getDate()->format('H:i');
        $dto->date = $workStatus->getDate()->format('j-m-Y');
        $dto->day = $workStatus->getDate()->format('l');
        $dto->userName = $workStatus->getUser()->getFullName();
        $dto->department = $workStatus->getUser()->getDepartment()->getName();
        $dto->sendBy = $sendBy;

        return $dto;
    }
}