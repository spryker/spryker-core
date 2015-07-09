<?php

namespace SprykerFeature\Zed\Queue\Business\Provider;

use SprykerFeature\Zed\Queue\Dependency\Plugin\TaskPluginInterface;

interface TaskProviderInterface
{

    /**
     * @param string $taskName
     *
     * @return TaskPluginInterface
     */
    public function getTaskByQueueName($taskName);

}
