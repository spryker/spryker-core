<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\SignalHandler;

use Spryker\Zed\Queue\Business\Exception\ExtensionNoLoadedException;
use Spryker\Zed\Queue\Business\Exception\FunctionIsDisabledException;
use Spryker\Zed\Queue\Business\Process\ProcessManagerInterface;
use Spryker\Zed\Queue\Business\Worker\Worker;
use Spryker\Zed\Queue\QueueConfig;

class WorkerSignalHandler implements WorkerSignalHandlerInterface
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
     * @param \Spryker\Zed\Queue\Business\Process\ProcessManagerInterface $processManager
     * @param \Spryker\Zed\Queue\QueueConfig $queueConfig
     * @param array $queueNames
     *
     * @throws \Spryker\Zed\Queue\Business\Exception\ExtensionNoLoadedException
     * @throws \Spryker\Zed\Queue\Business\Exception\FunctionIsDisabledException
     */
    public function __construct(
        ProcessManagerInterface $processManager,
        QueueConfig $queueConfig,
        array $queueNames
    ) {
        if (!extension_loaded('pcntl')) {
            throw new ExtensionNoLoadedException();
        }

        $disabledFunctions = explode(',', ini_get('disable_functions'));

        if (in_array('pcntl_signal', $disabledFunctions)) {
            throw new FunctionIsDisabledException();
        }

        pcntl_async_signals(true);

        $this->processManager = $processManager;
        $this->queueConfig = $queueConfig;
        $this->queueNames = $queueNames;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $queueProcesses = array_flip($this->queueNames);

        while ($queueProcesses) {
            usleep((int)$this->queueConfig->getQueueWorkerInterval() * Worker::SECOND_TO_MILLISECONDS);

            foreach ($queueProcesses as $queueName => $queueIndex) {
                $busyProcessIndex = $this->processManager->getBusyProcessNumber($queueName);

                if (!$busyProcessIndex) {
                    unset($queueProcesses[$queueName]);

                    if (!count($queueProcesses)) {
                        $queueProcesses['test'] = 'test';
                    }
                }
            }
        }

        exit(0);
    }

    /**
     * @param int[] $signals
     * @param callable $handler
     *
     * @return void
     */
    public function attach(array $signals, callable $handler): void
    {
        foreach ($signals as $signal) {
            pcntl_signal($signal, $handler);
        }
    }
}
