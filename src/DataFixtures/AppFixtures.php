<?php

namespace App\DataFixtures;

use App\Entity\Todo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 15; $i++) { 
            $todo = new Todo();
            $todo->setTask('tâche n°'.$i)
                 ->setDueDate(new \DateTimeImmutable())
                 ->setState(false);
            $manager->persist($todo);
        }

        $manager->flush();
    }
}
