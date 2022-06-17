<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ManagementController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/management", name="management")
     */
    public function index()
    {
        $users = $this->em->getRepository(User::class)->findBy([]);
        $departments = $this->em->getRepository(Department::class)->findAll();

        return $this->render('management/index.html.twig', [
            'users' =>$users,
            'departments'=>$departments,
            'controller_name' => 'ManagementController',
        ]);
    }


}
