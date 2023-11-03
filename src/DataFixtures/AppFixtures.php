<?php

namespace App\DataFixtures;

use App\Entity\Status;
use App\Entity\Task;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $task = new Task();
        $task->setTitle('1 task')
        ->setCreatedAt(new DateTimeImmutable())
        ->setStatus(Status::New);
        $manager->persist($task);


        $task2 = new Task();
        $task2->setTitle('2 task')
        ->setCreatedAt(new DateTimeImmutable())
        ->setStatus(Status::New);
        $manager->persist($task2);

        $manager->flush();
    }
}
