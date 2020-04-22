<?php

namespace App\Controller;

use App\Entity\Department;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/department", name="department")
     */
    public function index()
    {
        return $this->render('department/index.html.twig', [
            'controller_name' => 'DepartmentController',
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @Route("/department/new",name="department_new",methods={"POST"} )
     * @return JsonResponse
     */
    public function new(Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        if ($request->isXmlHttpRequest()) {
            $name = $request->request->get('name');
            $department = new Department();
            $department->setName($name);

            $error = $validator->validate($department);

            if ($error->count() > 0) {
                $errMessage = $error->get(0)->getMessage();

                    return new JsonResponse(['error' => (string)$errMessage]);
            }

            $em->persist($department);
            $em->flush();

            return new JsonResponse(['name' => $name]);
        }
    }
}
