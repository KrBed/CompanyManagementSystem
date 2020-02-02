<?php

namespace App\Controller;

use App\Entity\WorkStatus;
use App\ViewModels\WorkStatusDto;
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
     * @Route("/", name="overview")
     */
    public function index()
    {
        return $this->render('overview/index.html.twig', [
            'controller_name' => 'OverviewController',
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

            $workStatus = new WorkStatus();
            $workStatus->setUser($user = $this->getUser());
            $workStatus->setStatus($request->request->get('status'));
            $workStatus->setDate(new \DateTime());
            $workStatusDto = WorkStatusDto::createWorkStatusDto($workStatus,'RCP');
            $data = json_encode($workStatusDto);

            return new JsonResponse($data);
        }

        return new JsonResponse([]);
    }
}
