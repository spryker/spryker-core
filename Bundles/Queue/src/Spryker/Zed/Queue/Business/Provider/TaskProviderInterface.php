<?php

namespace Spryker\Zed\Queue\Business\Provider;

use Spryker\Zed\Queue\Dependency\Plugin\TaskPluginInterface;

interface TaskProviderInterface
{

    /**
     * @param string $taskName
     *
     * @return TaskPluginInterface
     */
    public function getTaskByQueueName($taskName);

}
