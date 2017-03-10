<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Queue\Business\Model\Process\ProcessManager;
use Spryker\Zed\Queue\Business\Model\Process\ProcessManagerInterface;
use Spryker\Zed\Queue\Business\Model\Task\Task;
use Spryker\Zed\Queue\Business\Model\Worker\Worker;
use Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface;
use Spryker\Zed\Queue\QueueConfig;
use Spryker\Zed\Queue\QueueDependencyProvider;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method QueueConfig getConfig()
 */
class QueueBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @var string
     */
    protected static $serverUniqueId;

    /**
     * @return Task
     */
    public function createTask()
    {
        return new Task(
            $this->getQueueClient(),
            $this->getConfig(),
            $this->getProcessorMessagePlugins()
        );
    }

    /**
     * @param OutputInterface $output
     *
     * @return Worker
     */
    public function createWorker(OutputInterface $output)
    {
        return new Worker(
            $this->createProcessManager(),
            $this->getQueueNames(),
            $this->getQueueWorkerConfig(),
            $output
        );
    }

    /**
     * @return ProcessManagerInterface
     */
    public function createProcessManager()
    {
        return new ProcessManager(
            $this->getQueryContainer(),
            $this->getServerUniqueId()
        );
    }

    /**
     * @return array
     */
    public function getQueueWorkerConfig()
    {
        return $this->getConfig()->getQueueWorkerConfig();
    }

    /**
     * @return string
     */
    public function getServerUniqueId()
    {
        if (static::$serverUniqueId === null) {
            static::$serverUniqueId = $this->getConfig()->getQueueServerId();
        }

        return static::$serverUniqueId;
    }

    /**
     * @return array
     */
    public function getQueueNames()
    {
        return array_keys($this->getProcessorMessagePlugins());
    }

    /**
     * @return QueueClientInterface
     */
    public function getQueueClient()
    {
        return $this->getProvidedDependency(QueueDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return QueueMessageProcessorPluginInterface[]
     */
    public function getProcessorMessagePlugins()
    {
        return $this->getProvidedDependency(QueueDependencyProvider::QUEUE_MESSAGE_PROCESSOR_PLUGINS);
    }
}
