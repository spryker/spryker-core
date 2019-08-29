<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Worker;

use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Shared\Queue\QueueConfig as SharedQueueConfig;
use Spryker\Zed\Queue\Business\Process\ProcessManagerInterface;
use Spryker\Zed\Queue\QueueConfig;

/**
 * @method \Spryker\Zed\Queue\Business\QueueBusinessFactory getFactory()
 */
class Worker implements WorkerInterface
{
    public const DEFAULT_MAX_QUEUE_WORKER = 1;
    public const SECOND_TO_MILLISECONDS = 1000;
    public const PROCESS_BUSY = 'busy';
    public const PROCESS_NEW = 'new';
    public const PROCESSES_INSTANCES = 'processes';
    public const RETRY_INTERVAL_SECONDS = 5;

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
     * @param array $options
     * @param int $round
     * @param array $processes
     *
     * @return void
     */
    public function start(string $command, array $options = [], int $round = 1, array $processes = []): void
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
            $isEmptyQueue = $this->isEmptyQueue($pendingProcesses, $options);

            if ($isEmptyQueue) {
                return;
            }

            if ($loopPassedSeconds >= 1) {
                $this->workerProgressBar->advance(1);
                $totalPassedSeconds++;
                $startTime = $this->getFreshMicroTime();
            }
            usleep($delayIntervalMilliseconds * static::SECOND_TO_MILLISECONDS);
            $loopPassedSeconds = $this->getFreshMicroTime() - $startTime;
        }

        $this->workerProgressBar->finish();
        $this->processManager->flushIdleProcesses();
        $this->waitForPendingProcesses($pendingProcesses, $command, $round, $delayIntervalMilliseconds, $options);
    }

    /**
     * @param \Symfony\Component\Process\Process[] $processes
     * @param string $command
     * @param int $round
     * @param int $delayIntervalSeconds
     * @param string[] $options
     *
     * @return void
     */
    protected function waitForPendingProcesses(
        array $processes,
        string $command,
        int $round,
        int $delayIntervalSeconds,
        array $options = []
    ): void {
        usleep($delayIntervalSeconds * static::SECOND_TO_MILLISECONDS);
        $pendingProcesses = $this->getPendingProcesses($processes);

        if (count($pendingProcesses) > 0) {
            $isWorkerLoopEnabled = $this->isWorkerLoopEnabled($options);
            if ($isWorkerLoopEnabled) {
                $this->workerProgressBar->reset();
                $this->start($command, $options, ++$round, $pendingProcesses);
            }

            sleep(static::RETRY_INTERVAL_SECONDS);
            $this->waitForPendingProcesses($processes, $command, $round, $delayIntervalSeconds, $options);
        }
    }

    /**
     * @param \Symfony\Component\Process\Process[] $processes
     *
     * @return \Symfony\Component\Process\Process[]
     */
    protected function getPendingProcesses(array $processes): array
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
    protected function executeOperation(string $command): array
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
    protected function startProcesses(string $command, string $queue): array
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
    protected function getMaxQueueWorker(string $queueName): int
    {
        $adapterConfiguration = $this->queueConfig->getQueueAdapterConfiguration();

        if (empty($adapterConfiguration) || !array_key_exists($queueName, $adapterConfiguration)) {
            $adapterConfiguration = $this->getQueueAdapterDefaultConfiguration($queueName);
        }

        $queueAdapterConfiguration = $adapterConfiguration[$queueName];

        if (!array_key_exists(SharedQueueConfig::CONFIG_MAX_WORKER_NUMBER, $queueAdapterConfiguration)) {
            return static::DEFAULT_MAX_QUEUE_WORKER;
        }

        return $queueAdapterConfiguration[SharedQueueConfig::CONFIG_MAX_WORKER_NUMBER];
    }

    /**
     * @param string $queueName
     *
     * @return array
     */
    protected function getQueueAdapterDefaultConfiguration(string $queueName): array
    {
        $adapterConfiguration = $this->queueConfig->getDefaultQueueAdapterConfiguration();

        if (!empty($adapterConfiguration)) {
            return [
                $queueName => $adapterConfiguration,
            ];
        }

        return [];
    }

    /**
     * @return float
     */
    protected function getFreshMicroTime(): float
    {
        return microtime(true);
    }

    /**
     * @param array $pendingProcesses
     * @param array $options
     *
     * @return bool
     */
    protected function isEmptyQueue(array $pendingProcesses, array $options): bool
    {
        return count($pendingProcesses) === 0 && $this->isWorkerStopsWhenEmptyQueueEnabled($options);
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    protected function isWorkerLoopEnabled(array $options): bool
    {
        return $this->queueConfig->getIsWorkerLoopEnabled() || $this->isWorkerStopsWhenEmptyQueueEnabled($options);
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    protected function isWorkerStopsWhenEmptyQueueEnabled(array $options): bool
    {
        return isset($options[SharedQueueConfig::CONFIG_WORKER_STOP_WHEN_EMPTY]) && $options[SharedQueueConfig::CONFIG_WORKER_STOP_WHEN_EMPTY];
    }
}
