<?php

namespace App\Controller;

use App\Entity\WorkStatus;
use App\Repository\WorkStatusRepository;
use App\ViewModels\WorkStatusDto;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

    public function __construct(EntityManagerInterface $em,WorkStatusRepository $workStatusRepository)
    {
        $this->em = $em;
        $this->workStatusRepository = $workStatusRepository;
    }

    /**
     * @Route("/", name="overview")
     */
    public function index()
    {
        $workStatuses = $this->workStatusRepository->findBy([],['date'=>'DESC']);

        $workStatusesDto = WorkStatusDto::createManyWorkStatuses($workStatuses);

        return $this->render('overview/index.html.twig', [
            'workStatuses' => $workStatusesDto
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
            $workStatus = new WorkStatus($this->getUser(),$request->request->get('status'),new \DateTime(),'RCP');

            $this->em->persist($workStatus);
            $this->em->flush();
            $workStatusDto = WorkStatusDto::createWorkStatusDto($workStatus,'RCP');
            $data = json_encode($workStatusDto);

            return new JsonResponse($data);
        }

        return new JsonResponse([]);
    }
}
