<?php

namespace App\Service;

use App\Entity\Shift;
use App\Entity\WorkRegistry;
use App\Repository\ShiftRepository;
use App\Repository\WorkStatusRepository;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

class UserStatisticService
{


    /**
     * @var ShiftRepository
     */
    private $shiftRepo;
    /**
     * @var WorkStatusRepository
     */
    private $statusRepo;

    public function __construct(ShiftRepository $shiftRepo, WorkStatusRepository $statusRepo){


        $this->shiftRepo = $shiftRepo;
        $this->statusRepo = $statusRepo;
    }
    /**
     * @param UserInterface|null $user
     * @param DateTime $date
     * @return mixed
     */
    public function getUserStatistic(?UserInterface $user, DateTime $date)
    {
        $today = new DateTime('now');
        $dateFrom = DateTimeService::getDateWithFirstDayOfMonth($date);
        $dateTo = DateTimeService::getDateWithLastDayOfMonth($date);

        $shifts = $this->shiftRepo->findUserShiftsByDate($user,$dateFrom,$dateTo);

        $payRate = $user->getPayRates()->toArray();
        $payRate = $payRate[0];

        $totalWorkTime = [];
        $overtime = [];
        $lateness = [];
        $presence = 0;
        $absence = 0;


        /**@var Shift $shift */
        foreach ($shifts as $shift){
            $shiftDate = $shift->getDate()->format('Y-m-d');

            if($shiftDate >= $today->format('Y-m-d')){
                break;
            }

            $workStatuses = $shift->getWorkStatuses()->toArray();

            if(!empty($workStatuses)){
                $presence ++;
            }else{
                $absence ++;
            }
            $dayWorkTime =[];

            $workStatuses = $this->filterWorkStatuses($workStatuses);

            $date = $shift->getDate()->format('Y-m-d');
            $startTime = $shift->getStartTime()->format('H:i:s');


            $timeFrom = DateTime::createFromFormat('Y-m-d H:i:s',$date .' '. $startTime);


            $startWork = null;
            /**@var $workStatus \App\Entity\WorkStatus */
            foreach ($workStatuses as $key => $workStatus) {
                $first = false;

                if($workStatus->getStatus() === WorkRegistry::ENTER_WORK){
                    $startWork = $workStatus->getDate();
                }
                // sprawdzenie spóźnienia
                if ($key === array_key_first($workStatuses)) {

                    $first = true;
                    if ($startWork > $timeFrom) {
                        $lateness[] = $timeFrom->diff($startWork)->format('%H:%i:%s');
                    }
                    $timeFrom = $startWork;
                }
                if (!$first) {
                    if($workStatus->getStatus() === WorkRegistry::EXIT_WORK){

                        $dayWorkTime[] = $timeFrom->diff($workStatus->getDate())->format('%H:%i:%s');
                    }else{
                        $timeFrom = $startWork;
                    }
                }
            }

            $dayWorkTime = $this->sumTimePeriods($dayWorkTime);

            $overtime[] = $this->checkOvertimeRate($dayWorkTime);
            $totalWorkTime[] = $dayWorkTime;
        }
        $overtime = $this->sumTimePeriods($overtime);
        $totalWorkTime = $this->sumTimePeriods($totalWorkTime);

        $workHours = $totalWorkTime['h'];

        if ($totalWorkTime['m'] > 30) {
            ++$workHours;
        }
        $overtimeHours = $overtime['h'];

        if ($overtime['m'] > 30) {
            ++$overtimeHours;
        }

        $payment = round($workHours * $payRate->getRatePerHour(),2) + round($overtimeHours * $payRate->getOvertimeRate(),2);

        return ['presence' => $presence,
                'absence' => $absence,
                'payment' =>$payment,
                'lateness'=>$this->sumTimePeriods($lateness),
                'overtime' => $overtime,
                'totalWorkTime' => $totalWorkTime ];
    }

    /**
     * @param array $totalWorkTime
     * @return void
     */
    public function sumTimePeriods(array $timePeriods)
    {

        $sum = strtotime('00:00:00');
        $totaltime = 0;

        foreach ($timePeriods as $element) {
            if(is_array($element)){
                $element = implode(':',$element);
            }
            // Converting the time into seconds
            $timeinsec = strtotime($element) - $sum;
            // Sum the time with previous value
            $totaltime = $totaltime + $timeinsec;
        }

        $h = (int)($totaltime / 3600);
        $totaltime -= ($h * 3600);
        $m = (int)($totaltime / 60);

        $s = $totaltime - ($m * 60);

        return ['h'=>$h,'m'=>$m,'s'=>$s];
    }

    /**
     * @param array $dayWorkTime
     * @return mixed
     */
    public function checkOvertimeRate(array $dayWorkTime)
    {
        $sum = strtotime('00:00:00');
            // Converting the time into seconds
        $dayWorkTime = implode(':',$dayWorkTime);
        $totalTime = strtotime($dayWorkTime) - $sum;
        // Sum the time with previous value
        $h = (int)($totalTime / 3600);
        if($h >= 8){
            $overtimeh = $h - 8;
            $totalTime -= ($h * 3600);
            $m = (int)($totalTime / 60);
            $s = $totalTime - ($m * 60);

            return ['h'=>$overtimeh,'m'=>$m,'s'=>$s];
        }
        return ['h'=>0,'m'=>0,'s'=>0];
    }

    /**
     * @param $workStatuses
     * @return void
     * funkcja filrująca statusy do potrzeby obliczeń, usuwająca niepotrzebne statusy
     */
    private function filterWorkStatuses($workStatuses)
    {
        // sortujemy statusy po dacie;
        usort($workStatuses, function($a1, $a2) {
            $value1 = strtotime($a1->getDate()->format('Y-m-d H:i:s'));
            $value2 = strtotime($a2->getDate()->format('Y-m-d H:i:s'));
            return $value1 - $value2;
        });

        $statusList =[];
        /**@var $status WorkStatus */
        foreach ($workStatuses as $key => $status){
            if (empty($statusList) && $status->getStatus() === WorkRegistry::EXIT_WORK){
                continue;
            }
            if(empty($statusList) && $status->getStatus() === WorkRegistry::ENTER_WORK){
                $statusList[] = $status;
            }else{
                $previousWs = $workStatuses[$key -1];
                if($previousWs->getStatus() !== $status->getStatus()){
                    $statusList[] = $status;
                }
            }
        }
        return $statusList;
    }
}