<?php

namespace App\Controller;

use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PositionController extends AbstractController
{
    /**
     * @Route("/position", name="position")
     */
    public function index()
    {
        return $this->render('position/index.html.twig', [
            'controller_name' => 'PositionController',
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @Route("/position/new", name="position_new",methods={"POST"})
     */
    public function new(Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        if ($request->isXmlHttpRequest()) {
            $name = $request->request->get('name');
            $position = new Position();
            $position->setName($name);

            $error = $validator->validate($position);

            if($error->count()>0){
                $errMessage = $error->get(0)->getMessage();

                if ($error->count() > 0) {
                    return new JsonResponse(['error' => (string)$errMessage]);
                }
            }

            $em->persist($position);
            $em->flush();

            return new JsonResponse(['name' => $name]);
        }


        return new JsonResponse([]);
    }
}