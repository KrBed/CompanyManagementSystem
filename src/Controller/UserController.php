<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    /**
     * @Route("admin/users/list", name="admin_users_list")
     */
    public function index(Request $request, EntityManagerInterface $em )
    {

        $users = $em->getRepository(User::class)->findBy([]);
        $departments = $em->getRepository(Department::class)->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'departments' =>$departments
        ]);
    }

    /**
     * @Route("admin/users/new" , name="admin_users_new")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);

        /** @var User $user */
        $user = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));

            $em->persist($user);
            $em->flush();

            if ($user->getId()) {
                $this->addFlash('success', 'User ' . $user->getFirstName() . $user->getLastName() . ' created');

                return $this->redirectToRoute('admin_users_list');
            } else {
                $this->addFlash('error', 'Something went wrong user was not created');

                return $this->render('user/new.html.twig', ['userForm' => $form->createView()]);
            }
        }
        return $this->render('user/new.html.twig', ['userForm' => $form->createView()]);
    }
}
