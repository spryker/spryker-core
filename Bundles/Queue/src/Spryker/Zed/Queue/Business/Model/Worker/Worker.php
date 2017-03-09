<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Model\Worker;

use Spryker\Shared\Queue\QueueConstants;
use Spryker\Zed\Queue\Business\QueueBusinessFactory;
use Symfony\Component\Process\Process;

/**
 * @method QueueBusinessFactory getFactory()
 */
class Worker implements WorkerInterface
{

    const DEFAULT_MAX_QUEUE_WORKER = 1;

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
    protected $queues;

    /**
     * @param array $queues
     * @param array $workerConfig
     */
    public function __construct(array $queues, array $workerConfig)
    {
        $this->queues = $queues;
        $this->maxThreshold = $workerConfig[QueueConstants::QUEUE_WORKER_MAX_THRESHOLD_SECONDS];
        $this->delayInterval = $workerConfig[QueueConstants::QUEUE_WORKER_INTERVAL_SECONDS];
        $this->processorWorker = $workerConfig[QueueConstants::QUEUE_WORKER_PROCESSOR];
        $this->outputFile = $workerConfig[QueueConstants::QUEUE_WORKER_OUTPUT_FILE];
    }

    /**
     * @param string $command
     *
     * @return void
     */
    public function start($command)
    {
        $startTime = time();
        $passedSeconds = 0;

        while ($passedSeconds < $this->maxThreshold) {
            $this->executeOperation($command);

            sleep($this->delayInterval);
            $passedSeconds = time() - $startTime;
        }

    }

    /**
     * @param string $command
     *
     * @return void
     */
    protected function executeOperation($command)
    {
        foreach ($this->queues as $queue) {
            $command = sprintf('%s %s >> %s', $command, $queue, $this->outputFile);
            $this->runCommand($command, $queue);
        }
    }

    /**
     * @param string $command
     * @param string $queue
     *
     * @return void
     */
    protected function runCommand($command, $queue)
    {
        $numberOfWorkers = $this->getMaxQueueWorker($queue);
        $this->startProcesses($command, $numberOfWorkers);
    }

    /**
     * @param string $command
     * @param int $numberOfWorkers
     *
     * @return void
     */
    protected function startProcesses($command, $numberOfWorkers)
    {
        for ($i = 0; $i < $numberOfWorkers; $i++) {
            $process = new Process($command);
            $process->start();
        }
    }

    /**
     * @param string $queue
     *
     * @return int|mixed
     */
    protected function getMaxQueueWorker($queue)
    {
        if (!array_key_exists($queue, $this->processorWorker)) {
            return self::DEFAULT_MAX_QUEUE_WORKER;
        }

        return $this->processorWorker[$queue];
    }
}
