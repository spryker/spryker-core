<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Worker;

use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Shared\Queue\QueueConfig as SharedConfig;
use Spryker\Zed\Queue\Business\Process\ProcessManagerInterface;
use Spryker\Zed\Queue\QueueConfig;

/**
 * @method \Spryker\Zed\Queue\Business\QueueBusinessFactory getFactory()
 */
class Worker implements WorkerInterface
{
    const DEFAULT_MAX_QUEUE_WORKER = 1;
    const SECOND_TO_MILLISECONDS = 1000;
    const PROCESS_BUSY = 'busy';
    const PROCESS_NEW = 'new';
    const PROCESSES_INSTANCES = 'processes';

    /**
     * @var \Spryker\Zed\Queue\Business\Process\ProcessManagerInterface
     */
    protected $processManager;

    /**
     * @var \Spryker\Zed\Queue\QueueConfig
     */
    protected $queueConfig;

    /**
     * @var \Spryker\Zed\Queue\Business\Worker\WorkerProgressBarInterface
     */
    protected $workerProgressBar;

    /**
     * @var \Spryker\Client\Queue\QueueClientInterface
     */
    protected $queueClient;

    /**
     * @var array
     */
    protected $queueNames;

    /**
     * @param \Spryker\Zed\Queue\Business\Process\ProcessManagerInterface $processManager
     * @param \Spryker\Zed\Queue\QueueConfig $queueConfig
     * @param \Spryker\Zed\Queue\Business\Worker\WorkerProgressBarInterface $workerProgressBar
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param array $queueNames
     */
    public function __construct(
        ProcessManagerInterface $processManager,
        QueueConfig $queueConfig,
        WorkerProgressBarInterface $workerProgressBar,
        QueueClientInterface $queueClient,
        array $queueNames
    ) {
        $this->processManager = $processManager;
        $this->workerProgressBar = $workerProgressBar;
        $this->queueConfig = $queueConfig;
        $this->queueClient = $queueClient;
        $this->queueNames = $queueNames;
    }

    /**
     * @param string $command
     * @param int $round
     * @param array $processes
     *
     * @return void
     */
    public function start($command, $round = 1, $processes = [])
    {
        $loopPassedSeconds = 0;
        $totalPassedSeconds = 0;
        $pendingProcesses = [];
        $startTime = $this->getFreshMicroTime();
        $maxThreshold = (int)$this->queueConfig->getQueueWorkerMaxThreshold();
        $delayIntervalMilliseconds = (int)$this->queueConfig->getQueueWorkerInterval();

        $this->workerProgressBar->start($maxThreshold, $round);
        while ($totalPassedSeconds < $maxThreshold) {
            $processes = array_merge($this->executeOperation($command), $processes);
            $pendingProcesses = $this->getPendingProcesses($processes);
            if ($loopPassedSeconds >= 1) {
                $this->workerProgressBar->advance(1);
                $totalPassedSeconds++;
                $startTime = $this->getFreshMicroTime();
            }
            usleep($delayIntervalMilliseconds * static::SECOND_TO_MILLISECONDS);
            $loopPassedSeconds = $this->getFreshMicroTime() - $startTime;
        }

        $this->workerProgressBar->finish();
        $this->waitForPendingProcesses($pendingProcesses, $command, $round, $delayIntervalMilliseconds);
        $this->processManager->flushIdleProcesses();
    }

    /**
     * @param \Symfony\Component\Process\Process[] $processes
     * @param string $command
     * @param int $round
     * @param int $delayIntervalSeconds
     *
     * @return void
     */
    protected function waitForPendingProcesses(array $processes, $command, $round, $delayIntervalSeconds)
    {
        usleep($delayIntervalSeconds * static::SECOND_TO_MILLISECONDS);
        $pendingProcesses = $this->getPendingProcesses($processes);

        if (count($pendingProcesses) > 0) {
            $this->workerProgressBar->reset();
            $this->start($command, ++$round, $pendingProcesses);
        }
    }

    /**
     * @param \Symfony\Component\Process\Process[] $processes
     *
     * @return \Symfony\Component\Process\Process[]
     */
    protected function getPendingProcesses($processes)
    {
        $pendingProcesses = [];
        foreach ($processes as $process) {
            if ($this->processManager->isProcessRunning($process->getPid())) {
                $pendingProcesses[] = $process;
            }
        }

        return $pendingProcesses;
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process[]
     */
    protected function executeOperation($command)
    {
        $this->workerProgressBar->refreshOutput(count($this->queueNames));

        $index = 0;
        $processes = [];
        foreach ($this->queueNames as $queue) {
            $processCommand = sprintf('%s %s', $command, $queue);

            if ($this->queueConfig->getQueueWorkerLogStatus()) {
                $processCommand = sprintf('%s >> %s', $processCommand, $this->queueConfig->getQueueWorkerOutputFileName());
            }

            $queueProcesses = $this->startProcesses($processCommand, $queue);
            $processes = array_merge($processes, $queueProcesses[static::PROCESSES_INSTANCES]);

            $this
                ->workerProgressBar
                ->writeConsoleMessage(
                    ++$index,
                    $queue,
                    $queueProcesses[static::PROCESS_BUSY],
                    $queueProcesses[static::PROCESS_NEW]
                );
        }

        return $processes;
    }

    /**
     * @param string $command
     * @param string $queue
     *
     * @return array
     */
    protected function startProcesses($command, $queue)
    {
        $busyProcessNumber = $this->processManager->getBusyProcessNumber($queue);
        $numberOfWorkers = $this->getMaxQueueWorker($queue) - $busyProcessNumber;

        $processes = [];
        $message = $this->queueClient->receiveMessage($queue, $this->queueConfig->getWorkerMessageCheckOption() ?: []);
        if ($message->getQueueMessage() !== null) {
            $this->queueClient->reject($message);
            for ($i = 0; $i < $numberOfWorkers; $i++) {
                usleep((int)$this->queueConfig->getQueueProcessTriggerInterval());
                $processes[] = $this->processManager->triggerQueueProcess($command, $queue);
            }
        } else {
            $numberOfWorkers = 0;
        }

        return [
            static::PROCESS_BUSY => $busyProcessNumber,
            static::PROCESS_NEW => $numberOfWorkers,
            static::PROCESSES_INSTANCES => $processes,
        ];
    }

    /**
     * @param string $queueName
     *
     * @return int
     */
    protected function getMaxQueueWorker($queueName)
    {
        $adapterConfiguration = $this->queueConfig->getQueueAdapterConfiguration();

        if (!array_key_exists($queueName, $adapterConfiguration)) {
            return static::DEFAULT_MAX_QUEUE_WORKER;
        }
        $queueAdapterConfiguration = $adapterConfiguration[$queueName];

        if (!array_key_exists(SharedConfig::CONFIG_MAX_WORKER_NUMBER, $queueAdapterConfiguration)) {
            return static::DEFAULT_MAX_QUEUE_WORKER;
        }

        return $queueAdapterConfiguration[SharedConfig::CONFIG_MAX_WORKER_NUMBER];
    }

    /**
     * @return float
     */
    protected function getFreshMicroTime()
    {
        return microtime(true);
    }
}
