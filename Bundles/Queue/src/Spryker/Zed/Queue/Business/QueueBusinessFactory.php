<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Queue\Business\Model\Process\ProcessManager;
use Spryker\Zed\Queue\Business\Model\Task\Task;
use Spryker\Zed\Queue\Business\Model\Worker\Worker;
use Spryker\Zed\Queue\QueueDependencyProvider;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Queue\QueueConfig getConfig()
 * @method \Spryker\Zed\Queue\Persistence\QueueQueryContainer getQueryContainer()
 */
class QueueBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @var string
     */
    protected static $serverUniqueId;

    /**
     * @return \Spryker\Zed\Queue\Business\Model\Task\Task
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
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Queue\Business\Model\Worker\Worker
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
     * @return \Spryker\Zed\Queue\Business\Model\Process\ProcessManagerInterface
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
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    public function getQueueClient()
    {
        return $this->getProvidedDependency(QueueDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface[]
     */
    public function getProcessorMessagePlugins()
    {
        return $this->getProvidedDependency(QueueDependencyProvider::QUEUE_MESSAGE_PROCESSOR_PLUGINS);
    }

}
