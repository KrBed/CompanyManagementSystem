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
     * @Route("/duty_rooster", name="duty_rooster")
     */
    public function index()
    {
        $users = $this->userRepository->findAll();
        $actualDate = new \DateTime();
        $month = $actualDate->format('m');
        $year = $actualDate->format('Y');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $monthDays = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dateToAdd = new \DateTime();
            $fDate = $dateToAdd->setDate($year, $month, $i)->format('d.m.');
            $fDay = $dateToAdd->setDate($year, $month, $i)->format('D');
            $monthDays[$fDate] = $fDay;

        }

        return $this->render('duty_rooster/index.html.twig', ['monthDays' => $monthDays,'users' => $users,'actualDate'=>$actualDate]);
    }
}
