<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\SignalHandler;

use Spryker\Zed\Queue\Business\Process\ProcessManagerInterface;
use Spryker\Zed\Queue\Business\Worker\Worker;
use Spryker\Zed\Queue\QueueConfig;

class QueueWorkerSignalDispatcher implements SignalDispatcherInterface
{
    /**
     * @var \Spryker\Zed\Queue\Business\Process\ProcessManagerInterface
     */
    protected $processManager;

    /**
     * @var \Spryker\Zed\Queue\QueueConfig
     */
    protected $queueConfig;

    /**
     * @var array
     */
    protected $queueNames;

    /**
     * @var bool
     */
    protected $isProcessRunning = false;

    /**
     * @param \Spryker\Zed\Queue\Business\Process\ProcessManagerInterface $processManager
     * @param \Spryker\Zed\Queue\QueueConfig $queueConfig
     * @param array $queueNames
     */
    public function __construct(
        ProcessManagerInterface $processManager,
        QueueConfig $queueConfig,
        array $queueNames
    ) {
        $this->processManager = $processManager;
        $this->queueConfig = $queueConfig;
        $this->queueNames = $queueNames;
    }

    /**
     * @param int[] $signals
     *
     * @return void
     */
    public function dispatch(array $signals): void
    {
        foreach ($signals as $signal) {
            if (function_exists('pcntl_signal')) {
                pcntl_async_signals(true);
                pcntl_signal($signal, [$this, 'waitForRunningProcessesAndExit']);
            }
        }
    }

    /**
     * @return void
     */
    public function waitForRunningProcessesAndExit(): void
    {
        $this->waitForRunningProcesses();

        exit(0);
    }

    /**
     * @return void
     */
    protected function waitForRunningProcesses(): void
    {
        if ($this->isProcessRunning) {
            return;
        }

        $this->isProcessRunning = true;
        $queueProcesses = array_flip($this->queueNames);

        while (count($queueProcesses) > 0) {
            usleep((int)$this->queueConfig->getQueueWorkerInterval() * Worker::SECOND_TO_MILLISECONDS);

            foreach (array_keys($queueProcesses) as $queueName) {
                $busyProcessIndex = $this->processManager->getBusyProcessNumber($queueName);

                if (!$busyProcessIndex) {
                    unset($queueProcesses[$queueName]);
                }
            }
        }

        $this->isProcessRunning = false;
    }
}
