<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DutyRoosterController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/duty/rooster", name="duty_rooster")
     */
    public function index()
    {
        $users = $this->userRepository->findAll();
        $date = new \DateTime();
        $month = $date->format('m');
        $year = $date->format('Y');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $monthDays = [];
        for ($i = 1;$i <= $daysInMonth; $i++){
            $dateToAdd= new \DateTime();
            $formatedDate = $dateToAdd->setDate($year,$month,$i)->format('Y-m-d');

            $monthDays[] = $formatedDate;
        }

        dd($monthDays);
        return $this->render('duty_rooster/index.html.twig', ['monthDays'=>$monthDays,
            'controller_name' => 'DutyRoosterController',
        ]);
    }
}
