<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Worker;

use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Shared\Queue\QueueConfig as SharedQueueConfig;
use Spryker\Zed\Queue\Business\Process\ProcessManagerInterface;
use Spryker\Zed\Queue\Business\Reader\QueueConfigReaderInterface;
use Spryker\Zed\Queue\Business\SignalHandler\SignalDispatcherInterface;
use Spryker\Zed\Queue\QueueConfig;

/**
 * @method \Spryker\Zed\Queue\Business\QueueBusinessFactory getFactory()
 */
class Worker implements WorkerInterface
{
    /**
     * @var int
     */
    public const DEFAULT_MAX_QUEUE_WORKER = 1;

    /**
     * @var int
     */
    public const SECOND_TO_MILLISECONDS = 1000;

    /**
     * @var string
     */
    public const PROCESS_BUSY = 'busy';

    /**
     * @var string
     */
    public const PROCESS_NEW = 'new';

    /**
     * @var string
     */
    public const PROCESSES_INSTANCES = 'processes';

    /**
     * @var int
     */
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
     * @var array<string>
     */
    protected $queueNames;

    /**
     * @var \Spryker\Zed\Queue\Business\SignalHandler\SignalDispatcherInterface
     */
    protected $signalDispatcher;

    /**
     * @var \Spryker\Zed\Queue\Business\Reader\QueueConfigReaderInterface
     */
    protected QueueConfigReaderInterface $queueConfigReader;

    /**
     * @var array<\Spryker\Zed\QueueExtension\Dependency\Plugin\QueueMessageCheckerPluginInterface>
     */
    protected $queueMessageCheckerPlugins;

    /**
     * @param \Spryker\Zed\Queue\Business\Process\ProcessManagerInterface $processManager
     * @param \Spryker\Zed\Queue\QueueConfig $queueConfig
     * @param \Spryker\Zed\Queue\Business\Worker\WorkerProgressBarInterface $workerProgressBar
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param array<string> $queueNames
     * @param \Spryker\Zed\Queue\Business\SignalHandler\SignalDispatcherInterface $signalDispatcher
     * @param \Spryker\Zed\Queue\Business\Reader\QueueConfigReaderInterface $queueConfigReader
     * @param array<\Spryker\Zed\QueueExtension\Dependency\Plugin\QueueMessageCheckerPluginInterface> $queueMessageCheckerPlugins
     */
    public function __construct(
        ProcessManagerInterface $processManager,
        QueueConfig $queueConfig,
        WorkerProgressBarInterface $workerProgressBar,
        QueueClientInterface $queueClient,
        array $queueNames,
        SignalDispatcherInterface $signalDispatcher,
        QueueConfigReaderInterface $queueConfigReader,
        array $queueMessageCheckerPlugins
    ) {
        $this->processManager = $processManager;
        $this->workerProgressBar = $workerProgressBar;
        $this->queueConfig = $queueConfig;
        $this->queueClient = $queueClient;
        $this->queueNames = $queueNames;
        $this->signalDispatcher = $signalDispatcher;
        $this->queueConfigReader = $queueConfigReader;
        $this->signalDispatcher->dispatch($this->queueConfig->getSignalsForGracefulWorkerShutdown());
        $this->queueMessageCheckerPlugins = $queueMessageCheckerPlugins;
    }

    /**
     * @param string $command
     * @param array<string, mixed> $options
     * @param int $round
     * @param array<\Symfony\Component\Process\Process> $processes
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

            if ($this->isEmptyQueue($pendingProcesses, $options)) {
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
     * @param array<\Symfony\Component\Process\Process> $processes
     * @param string $command
     * @param int $round
     * @param int $delayIntervalSeconds
     * @param array<string, mixed> $options
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
        static $waitTimeStart = 0;
        $waitTimeStart = $waitTimeStart ?: microtime(true);
        $maxWaitSeconds = $this->queueConfig->getQueueWorkerMaxWaitingSeconds();
        $maxWaitRounds = $this->queueConfig->getQueueWorkerMaxWaitingRounds();
        $waitLimitEnabled = $this->queueConfig->isQueueWorkerWaitLimitEnabled();
        $waitingLimitReached = $round > $maxWaitRounds || (microtime(true) - $waitTimeStart >= $maxWaitSeconds);
        if ($waitLimitEnabled && $waitingLimitReached) {
            // pending processes will be killed automatically
            $this->processManager->flushAllWorkerProcesses();

            return;
        }

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
     * @param array<\Symfony\Component\Process\Process> $processes
     *
     * @return array<\Symfony\Component\Process\Process>
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
     * @return array<\Symfony\Component\Process\Process>
     */
    protected function executeOperation(string $command): array
    {
        $this->workerProgressBar->refreshOutput(count($this->queueNames));

        $index = 0;
        $processes = [];
        foreach ($this->queueNames as $queue) {
            $processCommand = sprintf('%s %s', $command, $queue);

            if ($this->queueConfig->getQueueWorkerLogStatus()) {
                $processCommand = sprintf('%s >> %s 2>&1', $processCommand, $this->getQueueWorkerOutputFileNameBasedOnType());
            }

            $queueProcesses = $this->startProcesses($processCommand, $queue);
            $processes = array_merge($processes, $queueProcesses[static::PROCESSES_INSTANCES]);

            $this
                ->workerProgressBar
                ->writeConsoleMessage(
                    ++$index,
                    $queue,
                    $queueProcesses[static::PROCESS_BUSY],
                    $queueProcesses[static::PROCESS_NEW],
                );
        }

        return $processes;
    }

    /**
     * @return string
     */
    protected function getQueueWorkerOutputFileNameBasedOnType(): string
    {
        $outputFileName = $this->queueConfig->getQueueWorkerOutputFileName();
        if (is_resource($outputFileName)) {
            return stream_get_meta_data($outputFileName)['uri'];
        }

        return $outputFileName;
    }

    /**
     * @param string $command
     * @param string $queue
     *
     * @return array<string, mixed>
     */
    protected function startProcesses(string $command, string $queue): array
    {
        $busyProcessNumber = $this->processManager->getBusyProcessNumber($queue);
        $numberOfWorkers = $this->queueConfigReader->getMaxQueueWorkerByQueueName($queue) - $busyProcessNumber;

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
     * @return array<string, mixed>
     */
    protected function getQueueAdapterDefaultConfiguration(string $queueName): array
    {
        $adapterConfiguration = $this->queueConfig->getDefaultQueueAdapterConfiguration();

        if ($adapterConfiguration) {
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
     * @param array<\Symfony\Component\Process\Process> $pendingProcesses
     * @param array<string, mixed> $options
     *
     * @return bool
     */
    protected function isEmptyQueue(array $pendingProcesses, array $options): bool
    {
        if (!$this->isWorkerStopsWhenEmptyQueueEnabled($options) || $pendingProcesses) {
            return false;
        }

        return $this->areQueuesEmpty();
    }

    /**
     * @param string $queueName
     *
     * @return array<string, mixed>
     */
    protected function getQueueConfiguration(string $queueName): array
    {
        $adapterConfiguration = $this->queueConfig->getQueueAdapterConfiguration();

        if (!$adapterConfiguration || !array_key_exists($queueName, $adapterConfiguration)) {
            $adapterConfiguration = $this->getQueueAdapterDefaultConfiguration($queueName);
        }

        return $adapterConfiguration[$queueName];
    }

    /**
     * @return bool
     */
    protected function areQueuesEmpty(): bool
    {
        foreach ($this->queueMessageCheckerPlugins as $queueMessageCheckerPlugin) {
            if (
                $queueMessageCheckerPlugin->isApplicable(
                    $this->getQueueConfiguration($this->queueNames[0])[SharedQueueConfig::CONFIG_QUEUE_ADAPTER],
                )
            ) {
                return $queueMessageCheckerPlugin->areQueuesEmpty($this->queueNames);
            }
        }

        return true;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return bool
     */
    protected function isWorkerLoopEnabled(array $options): bool
    {
        return $this->queueConfig->getIsWorkerLoopEnabled() || $this->isWorkerStopsWhenEmptyQueueEnabled($options);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return bool
     */
    protected function isWorkerStopsWhenEmptyQueueEnabled(array $options): bool
    {
        return isset($options[SharedQueueConfig::CONFIG_WORKER_STOP_WHEN_EMPTY]) && $options[SharedQueueConfig::CONFIG_WORKER_STOP_WHEN_EMPTY];
    }
}
