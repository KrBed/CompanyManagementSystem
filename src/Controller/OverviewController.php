<?php

namespace App\Controller;

use App\Entity\WorkStatus;
use App\Repository\WorkStatusRepository;
use App\Service\DateTimeService;
use App\Service\ShiftService;
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
     */
    public function index()
    {
        $user = $this->security->getUser();
        $date = new \DateTime('now');
        $monthDays = DateTimeService::getDaysInMonth($date);
        $shiftsDto = $this->shiftService->createShiftDtoForUser($user, $date);
//        dump($shiftsDto);die;
        $workStatuses = $this->workStatusRepository->findBy([], ['date' => 'DESC']);

        $workStatusesDto = WorkStatusDto::createManyWorkStatuses($workStatuses);

        return $this->render('overview/index.html.twig', [
            'workStatuses' => $workStatusesDto,'shiftsDto'=>$shiftsDto,'monthDays'=>$monthDays
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @Route("/addStatus", name="add_status")
     */
    public function addStatus(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $workStatus = new WorkStatus($this->getUser(), $request->request->get('status'), new \DateTime(), 'RCP');

            $this->em->persist($workStatus);
            $this->em->flush();
            $workStatusDto = WorkStatusDto::createWorkStatusDto($workStatus, 'RCP');
            $data = json_encode($workStatusDto);

            return new JsonResponse($data);
        }

        return new JsonResponse([]);
    }
}
