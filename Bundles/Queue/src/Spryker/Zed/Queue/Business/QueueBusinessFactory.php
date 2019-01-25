<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Queue\Business\Process\ProcessManager;
use Spryker\Zed\Queue\Business\QueueDumper\QueueDumper;
use Spryker\Zed\Queue\Business\QueueDumper\QueueDumperInterface;
use Spryker\Zed\Queue\Business\Task\TaskManager;
use Spryker\Zed\Queue\Business\Worker\Worker;
use Spryker\Zed\Queue\Business\Worker\WorkerProgressBar;
use Spryker\Zed\Queue\Dependency\Service\QueueToUtilEncodingServiceInterface;
use Spryker\Zed\Queue\QueueDependencyProvider;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Queue\QueueConfig getConfig()
 * @method \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface getQueryContainer()
 */
class QueueBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @var string
     */
    protected static $serverUniqueId;

    /**
     * @return \Spryker\Zed\Queue\Business\Task\TaskManager
     */
    public function createTask()
    {
        return new TaskManager(
            $this->getQueueClient(),
            $this->getConfig(),
            $this->getProcessorMessagePlugins()
        );
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Queue\Business\Worker\Worker
     */
    public function createWorker(OutputInterface $output)
    {
        return new Worker(
            $this->createProcessManager(),
            $this->getConfig(),
            $this->createWorkerProgressbar($output),
            $this->getQueueClient(),
            $this->getQueueNames()
        );
    }

    /**
     * @return \Spryker\Zed\Queue\Business\Process\ProcessManagerInterface
     */
    public function createProcessManager()
    {
        return new ProcessManager(
            $this->getQueryContainer(),
            $this->getServerUniqueId()
        );
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Queue\Business\Worker\WorkerProgressBarInterface
     */
    public function createWorkerProgressbar(OutputInterface $output)
    {
        return new WorkerProgressBar($output);
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

    /**
     * @return \Spryker\Zed\Queue\Business\QueueDumper\QueueDumperInterface
     */
    public function createQueueDumper(): QueueDumperInterface
    {
        return new QueueDumper(
            $this->getQueueClient(),
            $this->getConfig(),
            $this->getUtilEncodingService(),
            $this->getProcessorMessagePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Queue\Dependency\Service\QueueToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): QueueToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(QueueDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
