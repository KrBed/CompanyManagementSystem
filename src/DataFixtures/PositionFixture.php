<?php

namespace App\DataFixtures;

use App\Entity\Position;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PositionFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $departmentsNames = array('Brak stanowiska','Administrator','Kierownik','Księgowy','Informatyk','Programista','Pracownik produkcji');

        foreach ($departmentsNames as $name){
            $department = new Position();
            $department->setName($name);
            $manager->persist($department);
        }
        $manager->flush();

        $manager->flush();
    }
}
