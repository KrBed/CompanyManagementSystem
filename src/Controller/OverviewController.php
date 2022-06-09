<?php

namespace App\Controller;

use App\Entity\WorkStatus;
use App\Repository\ShiftRepository;
use App\Repository\WorkStatusRepository;
use App\Service\DateTimeService;
use App\Service\ShiftService;
use App\Service\UserStatisticService;
use App\ViewModels\WorkStatusDto;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class OverviewController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class OverviewController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var WorkStatusRepository
     */
    private $workStatusRepository;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var ShiftService
     */
    private $shiftService;

    public function __construct(EntityManagerInterface $em, WorkStatusRepository $workStatusRepository, Security $security, ShiftService $shiftService)
    {
        $this->em = $em;
        $this->workStatusRepository = $workStatusRepository;
        $this->security = $security;
        $this->shiftService = $shiftService;
    }

    /**
     * @Route("/", name="overview")
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
            'workStatuses' => $workStatusesDto,
            'shiftsDto' => $shiftsDto,
            'monthDays' => $monthDays,
            'actualDate' => $date,
            'timesheetView' => $timesheetView,
            'userStatisticView' => $userStatisticView,
            'companyStatisticView' => $companyStatisticView
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @Route("/addStatus", name="add_status")
     */
    public function addStatus(Request $request,ShiftRepository $shiftRepo)
    {
        if ($request->isXmlHttpRequest()) {
            $dateFrom = new \DateTime('now');
            $dateTo = new \DateTime('now');
            $dateFrom->setTime(0,0,0);
            $dateTo->setTime(23,59,59);

            $shift = $shiftRepo->findUserShiftsByDate($this->getUser(),$dateFrom,$dateTo);
            if(!empty($shift)){
                $shift = $shift[0];
            }else{
                $shift = null;
            }

            $workStatus = new WorkStatus($this->getUser(),$shift, $request->request->get('status'), new \DateTime(), 'RCP');

            $this->em->persist($workStatus);
            $this->em->flush();
            $workStatusDto = WorkStatusDto::createWorkStatusDto($workStatus, 'RCP');
            $data = json_encode($workStatusDto);

            return new JsonResponse($data);
        }

        return new JsonResponse([]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/overview/userStatistics/{date}/{direction}/", name="user_statistics",methods="GET",defaults={"direction"=null})
     */
    public function userStatisticsAction(Request $request,UserStatisticService $userService){

        $user = $this->security->getUser();
        $actualDate = DateTimeService::getDateFromDateString($request->get('date'),$request->get('direction'));

        $statistics = $userService->getUserStatistic($user,$actualDate);

        $statisticsView = $this->renderView('overview/user_statistic.html.twig',['statistics'=>$statistics,'actualDate'=>$actualDate]);

        return new JsonResponse($statisticsView);
    }
}
