<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
