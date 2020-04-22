<?php


namespace App\Service;


use App\Dto\ShiftDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;

class ShiftService
{

    /**
     * @var UserRepository
     */
    private  $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public  function getUserDtoWithShiftsInMonth($users, \DateTime $date)
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
}