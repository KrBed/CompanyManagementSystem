<?php

namespace App\Controller;

use App\Entity\PayRate;
use App\Entity\User;
use App\Form\UserFormType;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("admin/user/new")
     * @param User $user
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder){


        $form= $this->createForm(UserFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
//           /** @var User $user */
//            dd($form->getData());
//            dd($user);
        }
        /** @var User  $user */
       $user2 = new User();
       $payRate = new PayRate();
       $payRate->setObtainFrom(new DateTime('now'));
       $payRate->setRatePerHour(20);
       $payRate->setOvertimeRate(40);
        $payRate2 = new PayRate();
        $payRate2->setObtainFrom(new DateTime('now'));
        $payRate2->setRatePerHour(30);
        $payRate2->setOvertimeRate(60);
       $user2->addPayRate($payRate);
        $user2->addPayRate($payRate2);
//       dd($user);
       $form = $this->createForm(UserFormType::class,$user2);
        return $this->render('user/new.html.twig',['userForm'=>$form->createView()]);
    }
}
