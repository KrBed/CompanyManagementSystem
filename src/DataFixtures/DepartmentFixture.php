<?php

namespace App\DataFixtures;

use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DepartmentFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
       $departmentsNames = array('Brak działu','Księgowość','Produkcja','IT','Zaopatrzenie','Serwis');

       foreach ($departmentsNames as $name){
           $department = new Department();
           $department->setName($name);
           $manager->persist($department);
       }
        $manager->flush();
    }
}
