<?php

namespace SprykerFeature\Zed\Queue\Business\Provider;

use SprykerFeature\Zed\Queue\Dependency\Plugin\TaskPluginInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

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
     * @param $queueName
     *
     * @return TaskPluginInterface
     */
    public function getTaskByQueueName($queueName)
    {
        if (!array_key_exists($queueName, $this->tasks)) {
            throw new Exception;
        }
        return $this->tasks[$queueName];
    }

    /**
     * @param TaskPluginInterface $task
     */
    protected function addTask(TaskPluginInterface $task)
    {
        if (array_key_exists($task->getQueueName(), $this->tasks)) {
            throw new Exception;
        }
        $this->tasks[$task->getName()] = $task;
    }
}
