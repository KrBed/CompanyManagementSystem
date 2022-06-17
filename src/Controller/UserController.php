<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\User;
use App\Form\UserEditFormType;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Repository\WorkStatusRepository;
use App\Service\DateTimeService;
use App\Service\UserStatisticService;
use App\ViewModels\WorkStatusDto;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
    /**
     * @var UserStatisticService
     */
    private $userStatsService;
    /**
     * @var WorkStatusRepository
     */
    private $statusRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository,UserStatisticService $userStatsService,WorkStatusRepository $statusRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->userStatsService = $userStatsService;
        $this->statusRepository = $statusRepository;
    }

    /**
     * @param $id
     * @Route("admin/users/{id}/edit", name="admin_users_edit",methods={"GET","POST"}, defaults={"user"=null})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request,UserPasswordEncoderInterface $passwordEncoder, $user,$id){

        if($request->get('Method') == 'POST'){
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            $user->setUpdatedAt(new \DateTime('now'));
            $this->em->persist($user);
            $this->em->flush();
        }

        $user = $this->userRepository->findOneBy(['id' => $id]);

        $form = $this->createForm(UserEditFormType::class, $user);

        return $this->render('user/edit.html.twig',['userForm'=>$form->createView()]);
    }
    /**
     * @Route("admin/users/{id}" , name="admin_users_delete",methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
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
     * @Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_USER')")
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
     * @IsGranted("ROLE_ADMIN")
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

    /**
     * @Route("admin/users/info/{id}/{date}/{direction}" , name="admin_users_info",defaults={ "date"= 0 , "direction"=0 })
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @throws \Exception
     */
    public function info(Request $request, $id){

        $date = $request->get('date');
        $direction = $request->get('direction');

        if(empty($date) && empty($direction)){
           $date = new DateTime('now');
       }else{
           $date = DateTimeService::getDateFromDateString($date,$direction);
       }
        $user = $this->userRepository->findOneBy(['id'=>$id]);
        $payRates = $user->getPayRates()->toArray();

        $payRate = $payRates[0];

        $dateFrom = DateTimeService::getDateWithFirstDayOfMonth($date);
        $dateTo = DateTimeService::getDateWithLastDayOfMonth($date);
        $statistics = $this->userStatsService->getUserStatistic($user,$date);
        $workStatuses = $this->statusRepository->findWorkStatusByDate($user,$dateFrom,$dateTo);

        $workStatuses = WorkStatusDto::createManyWorkStatuses($workStatuses);

        $statisticsView = $this->renderView('management/user_statistics.html.twig',['statistics'=>$statistics,'actualDate'=>$date,'user' =>$user,'payRate'=>$payRate]);

       return $this->render('management/user_menagement_statistics.html.twig',['statistics'=>$statisticsView,'user'=>$user,'workStatuses'=>$workStatuses]);
    }
    /**
     * @Route("user/info/{date}/{direction}" , name="user_info",defaults={ "date"= 0 , "direction"=0 })
     * @Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_USER')")
     * @param Request $request
     * @throws \Exception
     */
    public function userInfo(Request $request){

        $date = $request->get('date');
        $direction = $request->get('direction');

        if(empty($date) && empty($direction)){
            $date = new DateTime('now');
        }else{
            $date = DateTimeService::getDateFromDateString($date,$direction);
        }
        $user = $this->getUser();
        $payRates = $user->getPayRates()->toArray();

        $payRate = $payRates[0];

        $dateFrom = DateTimeService::getDateWithFirstDayOfMonth($date);
        $dateTo = DateTimeService::getDateWithLastDayOfMonth($date);
        $statistics = $this->userStatsService->getUserStatistic($user,$date);
        $workStatuses = $this->statusRepository->findWorkStatusByDate($user,$dateFrom,$dateTo);

        $workStatuses = WorkStatusDto::createManyWorkStatuses($workStatuses);

        $statisticsView = $this->renderView('management/user_statistics.html.twig',['statistics'=>$statistics,'actualDate'=>$date,'user' =>$user,'payRate'=>$payRate]);

        return $this->render('management/user_menagement_statistics.html.twig',['statistics'=>$statisticsView,'user'=>$user,'workStatuses'=>$workStatuses]);
    }
}
