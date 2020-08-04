<?php


namespace App\Service;


use App\Dto\ShiftDto;
use App\Dto\UserDto;
use App\Entity\Shift;
use App\Entity\User;
use App\Repository\ShiftRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class ShiftService
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ShiftRepository
     */
    private $shiftRepository;

    public function __construct(UserRepository $userRepository, ShiftRepository $shiftRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->shiftRepository = $shiftRepository;
    }

    public function getUsersDtoWithShiftsInMonth($users, \DateTime $date)
    {

        $dateFrom = DateTimeService::getDateWithFirstDayOfMonth($date);
        $dateTo = DateTimeService::getDateWithLastDayOfMonth($date);
        $usersDto = array();
        $daysInMonth = DateTimeService::getDaysInMonth($date);
        /**
         * @var User $user
         */
        foreach ($users as $user) {

            $userShifts = $this->userRepository->getUserShiftsInMonth($user, $dateFrom, $dateTo);
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
            if (count($userShifts) != 0) {
                foreach ($user->getShifts() as $userShift) {
                    $date = $userShift->getDate()->format('d-m-Y');
                    foreach ($shiftsDto as $shiftDto) {
                        if ($date == $shiftDto->getDate()) {
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

    public function createShiftDtoForUser($user, \DateTime $date)
    {
        $shiftsDto = [];
        $dateFrom = DateTimeService::getDateWithFirstDayOfMonth($date);
        $dateTo = DateTimeService::getDateWithLastDayOfMonth($date);
        $daysInMonth = DateTimeService::getDaysInMonth($date);

        $shifts = $this->shiftRepository->findUserShiftsByDate($user, $dateFrom, $dateTo);
        if (count($shifts) != 0) {
            foreach ($daysInMonth as $key => $value) {
                $shiftDto = new ShiftDto();
                $shiftDto->setUserId($user->getId());
                $shiftDto->setDate($key);
                $shiftDto->setWeekDay($daysInMonth[$key]['day']);
                $shiftDto->setWeekDayNumber($daysInMonth[$key]['numericDay']);

                $shiftsDto[] = $shiftDto;
            }
            foreach ($shifts as $shift) {
                $date = $shift->getDate()->format('d-m-Y');
                foreach ($shiftsDto as $shiftDto) {

                    if ($date == $shiftDto->getDate()) {
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

    public function createShiftFromTimesheets($timesheets)
    {
        $shifts = [];
        foreach ($timesheets as $timesheet) {

            $dayFrom = (int)substr($timesheet['dateFrom'], 0, 2);
            $dayTo = (int)substr($timesheet['dateTo'], 0, 2);
            $month = (int)substr($timesheet['dateFrom'], 3, 2);
            $year = (int)substr($timesheet['dateFrom'], 6, 4);
            $workingDays = $timesheet['weekDays'];

            $startDate = new \DateTime();
            $startDate->setDate($year, $month, $dayFrom)->setTime(0, 0, 0, 0);
            $endDate = new \DateTime();
            $endDate->setDate($year, $month, $dayTo)->setTime(0, 0, 0, 0);

            $this->shiftRepository->removeUserShiftsByDate((int)$timesheet['userId'], $startDate, $endDate);

            $user = $this->userRepository->findOneBy(array('id' => $timesheet['userId']));

            for ($i = $dayFrom; $i <= $dayTo; $i++) {
                $date = new \DateTime();
                $dayOfWeek = $date->setDate($year, $month, $i)->format('w');
                if (in_array((int)$dayOfWeek, $workingDays, true)) {
                    $shift = new Shift();
                    $shift->setDate($date->setDate($year, $month, $i)->setTime(0, 0, 0, 0));
                    $shift->setUser($user);
                    if (array_key_exists('timeFrom', $timesheet)) {
                        $hourf = (int)substr($timesheet['timeFrom'], 0, 2);
                        $minutesf = (int)substr($timesheet['timeFrom'], 3, 2);
                        $startTime = new \DateTime();
                        $startTime->setDate($year, $month, $i)->setTime($hourf, $minutesf, 0, 0);
                        $shift->setStartTime($startTime);
                    }
                    if (array_key_exists('timeTo', $timesheet)) {
                        $hourt = (int)substr($timesheet['timeTo'], 0, 2);
                        $minutest = (int)substr($timesheet['timeTo'], 3, 2);
                        $endTime = new \DateTime();
                        $endTime->setDate($year, $month, $i)->setTime($hourt, $minutest, 0, 0);
                        $shift->setEndTime($endTime);
                    }
                    if (array_key_exists('numberOfHours', $timesheet)) {
                        $shift->setNumberOfHours($timesheet['numberOfHours']);
                    }
                    if (array_key_exists('countTimeAfterWork', $timesheet)) {
                        $shift->setCountTimeAfterWork($timesheet['countTimeAfterWork']);
                    }
                    if (array_key_exists('countTimeBeforeWork', $timesheet)) {
                        $shift->setCountTimeBeforeWork($timesheet['countTimeBeforeWork']);
                    }
                    if (array_key_exists('overtimeDutyRooster', $timesheet)) {
                        $shift->setOvertimeDutyRooster($timesheet['overtimeDutyRooster']);
                    }
                    $shifts[] = $shift;

                }

            }
        }
        return $shifts;
    }
}