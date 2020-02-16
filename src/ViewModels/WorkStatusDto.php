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

    static function createWorkStatusDto(WorkStatus $workStatus)
    {
        $dto = new self();
        $dto->name = $workStatus->getStatus();
        $dto->time = $workStatus->getDate()->format('H:i');
        $dto->date = $workStatus->getDate()->format('j-m-Y');
        $dto->day = $workStatus->getDate()->format('l');
        $dto->userName = $workStatus->getUser()->getFullName();
        $dto->department = $workStatus->getUser()->getDepartment()->getName();
        $dto->sendBy = $workStatus->getSendBy();

        return $dto;
    }

    static function createManyWorkStatuses($workStatuses){
        $workStatusesDto = array();
        foreach ($workStatuses as $workStatus){
            $date = $workStatus->getDate()->format('j-m-Y');
            if(in_array($date,$workStatusesDto,true)){
                $workStatusesDto[$date][] = self::createWorkStatusDto($workStatus);
            }else{
                $workStatusesDto[$date][] = self::createWorkStatusDto($workStatus);
            }
        }
        return $workStatusesDto;
    }
}