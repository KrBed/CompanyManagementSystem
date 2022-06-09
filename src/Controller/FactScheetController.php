<?php

namespace App\Controller;

use App\Service\UserStatisticService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FactScheetController extends AbstractController
{
    /**
     * @Route("/user-sheet", name="usersheet")
     * @param UserStatisticService $userService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserStatisticService $userService)
    {
        $user = $this->security->getUser();
        $date = new \DateTime('now');

        $monthDays = DateTimeService::getDaysInMonth($date);
        $shiftsDto = $this->shiftService->createShiftDtoForUser($user, $date);

        $workStatuses = $this->workStatusRepository->findBy([], ['date' => 'DESC']);

        $workStatusesDto = WorkStatusDto::createManyWorkStatuses($workStatuses);
        $userStatistic = $userService->getUserStatistic($user, $date);

        $timesheetView = $this->renderView('duty_rooster/timesheet.html.twig',['shiftsDto'=>$shiftsDto,'monthDays'=>$monthDays,'actualDate'=>$date->format('d-m-Y')]);
        $userStatisticView = $this->renderView('overview/user_statistic.html.twig',['actualDate'=>$date->format('d-m-Y'),'statistics'=>$userStatistic]);
        $companyStatisticView = $this->renderView('overview/company_statistic.html.twig',['actualDate'=>$date->format('d-m-Y'),'statistics'=>$userStatistic]);

        return $this->render('overview/index.html.twig', [
            'workStatuses' => $workStatusesDto,'shiftsDto'=>$shiftsDto,'monthDays'=>$monthDays,'actualDate'=>$date,
            'timesheetView'=>$timesheetView,'userStatisticView'=>$userStatisticView,'companyStatisticView'=>$companyStatisticView
        ]);
    }
}