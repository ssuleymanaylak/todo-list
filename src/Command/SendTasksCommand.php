<?php

namespace App\Command;

use App\Entity\Status;
use App\Repository\TaskRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

#[AsCommand(
    name: 'app:send-tasks',
    description: 'Sends tasks to the telegram',
)]
#[AsPeriodicTask('1 day', schedule: 'default', from: '08:00 Europe/Kyiv')]
class SendTasksCommand extends Command
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly NotifierInterface $notifier,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tasks = $this->taskRepository->findBy(['status' => Status::New], ['createdAt' => 'ASC'], 3);
        foreach ($tasks as $task) {
            $this->notifier->send(new Notification($task->getTitle(), ['chat/telegram']));
        }

        return Command::SUCCESS;
    }
}
