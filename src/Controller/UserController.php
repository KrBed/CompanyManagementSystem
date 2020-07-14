<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\User;
use App\Form\UserEditFormType;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
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
     * @param $id
     * @Route("admin/users/{id}/edit", name="admin_users_edit",methods={"GET"})
     */
    public function edit($id){

        $user = $this->userRepository->findOneBy(['id'=>$id]);

        $form = $this->createForm(UserEditFormType::class,$user);

        return $this->render('user/edit.html.twig',['userForm'=>$form->createView()]);
    }
    /**
     * @Route("admin/users/{id}" , name="admin_users_delete",methods={"DELETE"})
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete($id)
    {
        $user = $this->userRepository->findOneBy(['id'=>$id]);
        if($user){
            $payrates = $user->getPayRates();
            foreach ($payrates as $payrate){
                $this->em->remove($payrate);
            }
            $this->em->remove($user);
            $this->em->flush();
            return new JsonResponse(null,204);
        }
    }
    /**
     * @Route("admin/users/list", name="admin_users_list")
     */
    public function index(Request $request)
    {
        $users = $this->em->getRepository(User::class)->findBy([]);
        $departments = $this->em->getRepository(Department::class)->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'departments' => $departments
        ]);
    }

    /**
     * @Route("admin/users/new" , name="admin_users_new")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);

        /** @var User $user */
        $user = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            $user->setCreatedAt(new \DateTime('now'));
            $user->setUpdatedAt(new \DateTime('now'));
            $this->em->persist($user);
            $this->em->flush();

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
