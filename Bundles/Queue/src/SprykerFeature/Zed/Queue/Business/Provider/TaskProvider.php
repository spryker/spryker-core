<?php

namespace SprykerFeature\Zed\Queue\Business\Provider;

use SprykerFeature\Zed\Queue\Business\Exception\NoTaskConfiguredForGivenQueueException;
use SprykerFeature\Zed\Queue\Business\Exception\TaskAlreadyDefinedForQueueException;
use SprykerFeature\Zed\Queue\Dependency\Plugin\TaskPluginInterface;

class TaskProvider implements TaskProviderInterface
{

    /**
     * @var TaskPluginInterface[]
     */
    protected $tasks = [];

    /**
     * @param array $tasks
     */
    public function __construct(array $tasks)
    {
        foreach ($tasks as $task) {
            $this->addTask($task);
        }
    }

    /**
     * @param string $queueName
     *
     * @throws NoTaskConfiguredForGivenQueueException
     *
     * @return null|TaskPluginInterface
     */
    public function getTaskByQueueName($queueName)
    {
        if (!isset($this->tasks[$queueName])) {
            throw new NoTaskConfiguredForGivenQueueException(
                sprintf(
                    'No Task configured for given queue "%s"',
                    $queueName
                )
            );
        }

        return $this->tasks[$queueName];
    }

    /**
     * @param TaskPluginInterface $task
     *
     * @throws TaskAlreadyDefinedForQueueException
     *
     * @return void
     */
    protected function addTask(TaskPluginInterface $task)
    {
        if (isset($this->tasks[$task->getQueueName()])) {
            $definedTask = $this->tasks[$task->getQueueName()];
            throw new TaskAlreadyDefinedForQueueException(
                sprintf(
                    'Already defined %s Task for given queue %s.',
                    $definedTask->getName(),
                    $definedTask->getQueueName()
                )
            );
        }
        $this->tasks[$task->getQueueName()] = $task;
    }

}
