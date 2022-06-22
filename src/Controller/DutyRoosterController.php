<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\DateTimeService;
use App\Service\ShiftService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

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
    /**
     * @var Security
     */
    private $security;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        UserRepository $userRepository,
        ShiftService $shiftService,
        Security $security,
        TranslatorInterface $translator
    ) {
        $this->userRepository = $userRepository;
        $this->shiftService = $shiftService;
        $this->security = $security;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     * @Route("/duty_rooster/add",name="addRooster",methods={"POST"})
     */
    public function addRooster(Request $request, EntityManagerInterface $em, ShiftService $shiftService)
    {

        if ($request->isXmlHttpRequest()) {
            $timesheets = $request->request->get('timesheet');

            $now = new \DateTime('now');
            $now = $now->format('d-m-Y');
            $startDate = $timesheets[0]['dateFrom'];

            if(strtotime($now) > strtotime($startDate)){
                return new JsonResponse(['error' => 'Nie można dodać grafiku dla przeszłej daty'],Response::HTTP_ACCEPTED);
            }

            $shifts = $shiftService->createShiftFromTimesheets($timesheets);

            try {
                foreach ($shifts as $shift) {
                    $em->persist($shift);
                }
                $em->flush();
            } catch (\Exception $e) {
                throw $e;
            }


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

        if ($date == null) {
            $actualDate = new \DateTime();
        } else {
            $actualDate = DateTimeService::getDateFromDateString($date, null);
        }

        $monthDays = DateTimeService::getDaysInMonth($actualDate);
        $usersDto = $this->shiftService->getUsersDtoWithShiftsInMonth($users, $actualDate);
        return $this->render('duty_rooster/index.html.twig',
            ['monthDays' => $monthDays, 'users' => $users, 'usersDto' => $usersDto, 'actualDate' => $actualDate]);
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

        return $this->redirectToRoute('duty_rooster', ['date' => $changedDate->format('d-m-Y')]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @Route("/duty_rooster/getMonthTimesheetForUser/{date}/{direction}",name="getMonthTimesheetForUser",methods={"GET"},defaults={"direction"=null})
     */
    public function getMonthTimesheetForUser(Request $request, ShiftService $shiftService)
    {
        $user = $this->security->getUser();

        $monthDays = DateTimeService::getDaysInMonth(\DateTime::createFromFormat("d-m-Y", $request->get('date')));

        $actualDate = DateTimeService::getDateFromDateString($request->get('date'), $request->get('direction'));

        $shiftsDto = $shiftService->createShiftDtoForUser($user, $actualDate);
        $shiftsView = $this->renderView('duty_rooster/timesheet.html.twig',
            ['shiftsDto' => $shiftsDto, 'monthDays' => $monthDays, 'actualDate' => $actualDate->format('d-m-Y')]);

        return new JsonResponse([$shiftsView]);
    }
}
