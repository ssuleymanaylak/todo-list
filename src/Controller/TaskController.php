<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Task;
use App\Form\TaskType;
use App\Form\TaskUpdateType;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(Request $request, EntityManagerInterface $em, TaskRepository $taskRepository): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task
                ->setStatus(Status::New)
                ->setCreatedAt(new DateTimeImmutable());

            $em->persist($task);
            $em->flush();

            $this->addFlash('notice', 'Successfully added');

            return $this->redirect('task');
        }

        return $this->render('task/index.html.twig', [
            'task_form' => $form,
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/task/{id}', name: 'app_task_show')]
    public function show(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TaskUpdateType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('notice', 'Successfully edited');

            return $this->redirect('/task');
        }

        return $this->render('task/show.html.twig', [
            'task' => $task,
            'task_form' => $form,
        ]);
    }
}
