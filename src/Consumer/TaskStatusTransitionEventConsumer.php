<?php

namespace App\Consumer;

use App\Event\TaskStatusTransitionEvent;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Workflow\WorkflowInterface;

#[AsRemoteEventConsumer(name: 'telegram')]
class TaskStatusTransitionEventConsumer implements ConsumerInterface
{
    public function __construct(
        private TaskRepository $taskRepository,
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private WorkflowInterface $taskStateMachine,
    ) {
    }

    public function consume(RemoteEvent $event): void
    {
        if (!$event instanceof TaskStatusTransitionEvent) {
            throw new RuntimeException('Not supported event type');
        }

        $task = $this->taskRepository->find($event->taskId);

        if (!$this->taskStateMachine->can($task, $event->transition)) {
            $this->logger->error(
                'Status transition is not allowe',
                [
                    'id' => $event->taskId,
                    'transition' => $event->transition,
                ],
            );

            return;
        }

        $this->taskStateMachine->apply($task, $event->transition);
        $this->em->flush();
    }
}
