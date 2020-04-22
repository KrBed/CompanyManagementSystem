<?php

namespace App\Controller;

use App\Entity\DutyRooster;
use App\Entity\Shift;
use App\Entity\User;
use App\Repository\ShiftRepository;
use App\Repository\UserRepository;
use App\Service\DateTimeService;
use App\Service\ShiftService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DutyRoosterController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ShiftService
     */
    private $shiftService;

    public function __construct(UserRepository $userRepository,ShiftService $shiftService)
    {
        $this->userRepository = $userRepository;
        $this->shiftService = $shiftService;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     * @Route("/duty_rooster/add",name="addRooster",methods={"POST"})
     */
    public function addRooster(Request $request, EntityManagerInterface $em, ShiftRepository $shiftRepository)
    {
        if ($request->isXmlHttpRequest()) {
            $timesheets = $request->request->get('timesheet');

            foreach ($timesheets as $timesheet){
                $dayFrom = (int)substr($timesheet['dateFrom'], 0, 2);
                $dayTo = (int)substr($timesheet['dateTo'], 0, 2);
                $month = (int)substr($timesheet['dateFrom'], 3, 2);
                $year = (int)substr($timesheet['dateFrom'], 6, 4);
                $workingDays = $timesheet['weekDays'];

                $startDate = new \DateTime();
                $startDate->setDate($year,$month,$dayFrom)->setTime(0,0,0,0,);
                $endDate = new \DateTime();
                $endDate->setDate($year,$month,$dayTo)->setTime(0,0,0,0);

                $shiftRepository->removeUserShiftsByDate((int)$timesheet['userId'],$startDate,$endDate);
                $user = $this->userRepository->findOneBy(array('id'=>$timesheet['userId']));
                for ($i = $dayFrom; $i <= $dayTo; $i++) {
                    $date = new \DateTime();
                    $dayOfWeek = $date->setDate($year, $month, $i)->format('N');
                    if (in_array($dayOfWeek, $workingDays, true)) {
                        $shift = new Shift();
                        $shift->setDate($date->setDate($year,$month,$i)->setTime(0, 0, 0, 0));
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
                        $em->persist($shift);
                    }
                }
            }

            $em->flush();
            return new JsonResponse(['data' => $timesheets]);
        }
        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/duty_rooster/{date}", name="duty_rooster",defaults={"date"=null})
     */
    public function index($date)
    {
        $users = $this->userRepository->findAll();
        $actualDate = DateTimeService::getDateFromDateString($date);
        if ($date == null){
            $actualDate = new \DateTime();
        }
        $monthDays = DateTimeService::getDaysInMonth($actualDate);
        $usersDto = $this->shiftService->getUserDtoWithShiftsInMonth($users, $actualDate);
        return $this->render('duty_rooster/index.html.twig', ['monthDays' => $monthDays, 'users' => $users, 'usersDto' => $usersDto, 'actualDate' => $actualDate]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @Route("/duty_rooster/changeRoosterDate/{date}/{direction}",name="changeRoosterDate",methods={"GET"},defaults={"direction"=null})
     */
    public function changeRoosterDate($date, $direction)
    {
        $changedDate = DateTimeService::getDateFromDateString($date, $direction);

        return $this->redirectToRoute('duty_rooster',[ 'date' => $changedDate->format('d-m-Y')]);
    }
}
