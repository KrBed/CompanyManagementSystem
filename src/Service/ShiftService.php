<?php


namespace App\Service;


use App\Dto\ShiftDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\ShiftRepository;
use App\Repository\UserRepository;

class ShiftService
{

    /**
     * @var UserRepository
     */
    private  $userRepository;
    /**
     * @var ShiftRepository
     */
    private $shiftRepository;

    public function __construct(UserRepository $userRepository,ShiftRepository $shiftRepository)
    {
        $this->userRepository = $userRepository;
        $this->shiftRepository = $shiftRepository;
    }

    public  function getUsersDtoWithShiftsInMonth($users, \DateTime $date)
    {

        $dateFrom = DateTimeService::getDateWithFirstDayOfMonth($date);
        $dateTo = DateTimeService::getDateWithLastDayOfMonth($date);
        $usersDto = array();
        $daysInMonth = DateTimeService::getDaysInMonth($date);
        /**
         * @var User $user
         */
        foreach ($users as $user) {

            $userShifts = $this->userRepository->getUserShiftsInMonth($user, $dateFrom, $dateTo );
            /**
             * @var ShiftDto[] $shiftDto
             */
            $shiftsDto = array();
            /**
             * @var UserDto $userDto
             */
            $userDto = new UserDto();
            $userDto->setId($user->getId());
            $userDto->setFirstName($user->getFirstName());
            $userDto->setLastName($user->getLastName());
            $userDto->setDepartment($user->getDepartment());

            foreach ($daysInMonth as $key => $value) {
                $shiftDto = new ShiftDto();
                $shiftDto->setUserId($user->getId());
                $shiftDto->setDate($key);
                $shiftDto->setWeekDay($daysInMonth[$key]['day']);
                $shiftDto->setWeekDayNumber($daysInMonth[$key]['numericDay']);
                $shiftsDto[] = $shiftDto;
            }
            if(count($userShifts) != 0){
                foreach ($user->getShifts() as $userShift){
                    $date = $userShift->getDate()->format('d-m-Y');
                   foreach ($shiftsDto as $shiftDto ){
                       if($date == $shiftDto->getDate()){
                           $shiftDto->setOvertimeDutyRooster($userShift->getOvertimeDutyRooster());
                           $shiftDto->setCountTimeBeforeWork($userShift->getCountTimeBeforeWork());
                           $shiftDto->setCountTimeAfterWork($userShift->getCountTimeAfterWork());
                           $shiftDto->setTimeTo($userShift->getEndTime()->format('H:i'));
                           $shiftDto->setTimeFrom($userShift->getStartTime()->format('H:i'));
                           break;
                       }
                   }
                }
            }
            $userDto->setShifts($shiftsDto);
            $usersDto[] = $userDto;
        }
        return $usersDto;
    }

    public function createShiftDtoForUser($user,\DateTime $date){
        $shiftsDto = [];
        $dateFrom = DateTimeService::getDateWithFirstDayOfMonth($date);
        $dateTo = DateTimeService::getDateWithLastDayOfMonth($date);
        $daysInMonth = DateTimeService::getDaysInMonth($date);

        $shifts = $this->shiftRepository->findUserShiftsByDate($user,$dateFrom,$dateTo);
        if(count($shifts) != 0){
            foreach ($daysInMonth as $key => $value) {
                $shiftDto = new ShiftDto();
                $shiftDto->setUserId($user->getId());
                $shiftDto->setDate($key);
                $shiftDto->setWeekDay($daysInMonth[$key]['day']);
                $shiftDto->setWeekDayNumber($daysInMonth[$key]['numericDay']);

                $shiftsDto[] = $shiftDto;
            }
            foreach ($shifts as $shift){
                $date = $shift->getDate()->format('d-m-Y');
                foreach ($shiftsDto as $shiftDto ){

                    if($date == $shiftDto->getDate()){
                        $shiftDto->setOvertimeDutyRooster($shift->getOvertimeDutyRooster());
                        $shiftDto->setCountTimeBeforeWork($shift->getCountTimeBeforeWork());
                        $shiftDto->setCountTimeAfterWork($shift->getCountTimeAfterWork());
                        $shiftDto->setTimeTo($shift->getEndTime()->format('H:i'));
                        $shiftDto->setTimeFrom($shift->getStartTime()->format('H:i'));
                        break;
                    }
                }
            }
        }

        return $shiftsDto;
    }

    public function createShiftsForMonth($user,$daysInMonth){}
}