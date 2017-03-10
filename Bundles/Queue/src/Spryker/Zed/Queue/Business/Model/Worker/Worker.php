<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Model\Worker;

use Spryker\Shared\Queue\QueueConstants;
use Spryker\Zed\Queue\Business\Model\Process\ProcessManagerInterface;
use Spryker\Zed\Queue\Business\QueueBusinessFactory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @method QueueBusinessFactory getFactory()
 */
class Worker implements WorkerInterface
{

    const DEFAULT_MAX_QUEUE_WORKER = 1;

    /**
     * @var ProcessManagerInterface
     */
    protected $processManager;

    /**
     * @var int
     */
    protected $maxThreshold;

    /**
     * @var int
     */
    protected $delayInterval;

    /**
     * @var array
     */
    protected $processorWorker;

    /**
     * @var string
     */
    protected $outputFile;

    /**
     * @var array
     */
    protected $queueNames;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var ProgressBar
     */
    protected $progressBar;

    /**
     * @var bool
     */
    protected $firstRun = false;

    /**
     * @param ProcessManagerInterface $processManager
     * @param array $queues
     * @param array $workerConfig
     * @param OutputInterface $output
     */
    public function __construct(
        ProcessManagerInterface $processManager,
        array $queues,
        array $workerConfig,
        OutputInterface $output
    )
    {
        $this->processManager = $processManager;
        $this->queueNames = $queues;
        $this->output = $output;

        $this->maxThreshold = $workerConfig[QueueConstants::QUEUE_WORKER_MAX_THRESHOLD_SECONDS];
        $this->delayInterval = $workerConfig[QueueConstants::QUEUE_WORKER_INTERVAL_MILLISECONDS];
        $this->processorWorker = $workerConfig[QueueConstants::QUEUE_WORKER_PROCESSOR];
        $this->outputFile = $workerConfig[QueueConstants::QUEUE_WORKER_OUTPUT_FILE];
    }

    /**
     * @param int $round
     * @param string $command
     * @param array $processes
     *
     * @return void
     */
    public function start($command, $round = 1, $processes = [])
    {
        $startTime = time();
        $passedSeconds = 0;

        $this->progressBar = $this->createProgressBar();
        $this->progressBar->setMessage(sprintf('Main Queue Process <execution round #%d>:', $round));

        $pendingProcesses = [];
        while ($passedSeconds < $this->maxThreshold) {
            $processes = array_merge($this->executeOperation($command), $processes);
            $pendingProcesses = $this->getPendingProcesses($processes);
            $this->progressBar->advance();

            usleep($this->delayInterval * 1000);
            $passedSeconds = time() - $startTime;
        }

        $this->progressBar->finish();
        $this->waitForPendingProcesses($command, $round, $pendingProcesses);

        $this->processManager->flushIdleProcesses();
    }

    /**
     * @param string $command
     * @param int $round
     * @param Process[] $processes
     *
     * @return void
     */
    protected function waitForPendingProcesses($command, $round, array $processes)
    {
        usleep($this->delayInterval * 1000);
        $pendingProcesses = $this->getPendingProcesses($processes);

        if (count($pendingProcesses) > 0) {
            unset($this->progressBar);
            $this->start($command, ++$round, $pendingProcesses);
        }
    }

    /**
     * @param Process[] $processes
     *
     * @return Process[]
     */
    protected function getPendingProcesses($processes)
    {
        $pendingProcesses = [];
        foreach ($processes as $process) {
            if ($process->isRunning()) {
                $pendingProcesses[] = $process;
            }
        }

        return $pendingProcesses;
    }

    /**
     * @param string $command
     *
     * @return Process[]
     */
    protected function executeOperation($command)
    {
        $this->refreshOutput();

        $index = 0;
        $processes = [];
        foreach ($this->queueNames as $queue) {
            $processCommand = sprintf('%s %s >> %s', $command, $queue, $this->outputFile);
            $response = $this->startProcesses($processCommand, $queue);
            $processes = array_merge($processes,  $response['processes']);
            $this->writeConsoleMessage(++$index, $queue, $response['busy'], $response['new']);
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
        for ($i = 0; $i < $numberOfWorkers; $i++) {
            $processes[] = $this->processManager->triggerQueueProcess($command, $queue);
        }

        return [
            'busy' => $busyProcessNumber,
            'new' => $numberOfWorkers,
            'processes' => $processes
        ];
    }

    /**
     * @param string $queue
     *
     * @return int
     */
    protected function getMaxQueueWorker($queue)
    {
        if (!array_key_exists($queue, $this->processorWorker)) {
            return self::DEFAULT_MAX_QUEUE_WORKER;
        }

        return $this->processorWorker[$queue];
    }

    /**
     * @return ProgressBar
     */
    protected function createProgressBar()
    {
        $progressBar = new ProgressBar($this->output, $this->maxThreshold);
        $progressBar->setFormatDefinition('custom', '%message% %current%/%max% sec [%bar%] %percent:3s%%');
        $progressBar->setFormat('custom');

        return $progressBar;
    }

    /**
     * @return void
     */
    protected function refreshOutput()
    {
        if (!$this->firstRun) {
            $this->firstRun = true;

            return;
        }

        $this->output->write("\x0D");
        $this->output->write(str_repeat("\x1B[1A\x1B[2K", count($this->queueNames)));
    }

    /**
     * @param int $rowId
     * @param string $queueName
     * @param int $busyProcessNumber
     * @param int $newProcessNumber
     *
     * @return void
     */
    protected function writeConsoleMessage($rowId, $queueName, $busyProcessNumber, $newProcessNumber)
    {
        $this->output->writeln(
            sprintf(
                '[%d] %s queue process(es): New: %d Busy: %d Last Update: %s',
                $rowId,
                $queueName,
                $busyProcessNumber,
                $newProcessNumber,
                date('H:i:s')
            )
        );
    }
}
