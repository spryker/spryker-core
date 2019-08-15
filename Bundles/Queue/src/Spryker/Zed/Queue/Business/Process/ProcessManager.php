<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Process;

use Generated\Shared\Transfer\QueueProcessTransfer;
use Orm\Zed\Queue\Persistence\SpyQueueProcess;
use Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface;
use Symfony\Component\Process\Process;

class ProcessManager implements ProcessManagerInterface
{
    /**
     * @var \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var string
     */
    protected $serverUniqueId;

    /**
     * @param \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface $queryContainer
     * @param string $serverUniqueId
     */
    public function __construct(QueueQueryContainerInterface $queryContainer, $serverUniqueId)
    {
        $this->queryContainer = $queryContainer;
        $this->serverUniqueId = $serverUniqueId;
    }

    /**
     * @param string $command
     * @param string $queue
     *
     * @return \Symfony\Component\Process\Process
     */
    public function triggerQueueProcess($command, $queue)
    {
        $process = $this->createProcess($command);
        $process->start();

        if ($process->isRunning()) {
            $queueProcessTransfer = $this->createQueueProcessTransfer($queue, $process->getPid());
            $this->saveProcess($queueProcessTransfer);
        }

        return $process;
    }

    /**
     * @param string $queueName
     *
     * @return int
     */
    public function getBusyProcessNumber($queueName)
    {
        /** @var int[] $processIds */
        $processIds = $this->queryContainer
            ->queryProcessesByServerIdAndQueueName($this->serverUniqueId, $queueName)
            ->find();

        $busyProcessIndex = $this->releaseIdleProcesses($processIds);

        return $busyProcessIndex;
    }

    /**
     * @return void
     */
    public function flushIdleProcesses()
    {
        /** @var int[] $processIds */
        $processIds = $this->queryContainer
            ->queryProcessesByServerId($this->serverUniqueId)
            ->find();

        if (!empty($processIds)) {
            $this->releaseIdleProcesses($processIds);
        }
    }

    /**
     * @param array $processIds
     *
     * @return int
     */
    protected function releaseIdleProcesses($processIds)
    {
        $cleanupProcesses = [];
        $busyProcessIndex = 0;

        foreach ($processIds as $processId) {
            if ($this->isProcessRunning($processId)) {
                $busyProcessIndex++;
            } else {
                $cleanupProcesses[] = $processId;
            }
        }

        $this->deleteProcesses($cleanupProcesses);

        return $busyProcessIndex;
    }

    /**
     * @param int|null $processId
     *
     * @return bool
     */
    public function isProcessRunning($processId)
    {
        if ($processId === null) {
            return false;
        }

        $output = exec(sprintf('ps -p %s | grep %s | grep -v \'<defunct>\'', $processId, $processId));

        return trim($output) !== '';
    }

    /**
     * @param string $queue
     * @param int $processId
     *
     * @return \Generated\Shared\Transfer\QueueProcessTransfer
     */
    protected function createQueueProcessTransfer($queue, $processId)
    {
        $queueProcessTransfer = new QueueProcessTransfer();
        $queueProcessTransfer->setServerId($this->serverUniqueId);
        $queueProcessTransfer->setQueueName($queue);
        $queueProcessTransfer->setProcessPid($processId);
        $queueProcessTransfer->setWorkerPid(posix_getpgrp());

        return $queueProcessTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueProcessTransfer $queueProcessTransfer
     *
     * @return \Generated\Shared\Transfer\QueueProcessTransfer
     */
    protected function saveProcess(QueueProcessTransfer $queueProcessTransfer)
    {
        $processEntity = new SpyQueueProcess();
        $processEntity->fromArray($queueProcessTransfer->toArray());
        $processEntity->save();

        return $this->convertToQueueProcessTransfer($processEntity);
    }

    /**
     * @param \Orm\Zed\Queue\Persistence\SpyQueueProcess $processEntity
     *
     * @return \Generated\Shared\Transfer\QueueProcessTransfer
     */
    protected function convertToQueueProcessTransfer(SpyQueueProcess $processEntity)
    {
        $queueProcessTransfer = new QueueProcessTransfer();
        $queueProcessTransfer->fromArray($processEntity->toArray(), true);

        return $queueProcessTransfer;
    }

    /**
     * @param array $processIds
     *
     * @return int
     */
    protected function deleteProcesses(array $processIds)
    {
        return $this->queryContainer
            ->queryProcessesByProcessIds($processIds)
            ->delete();
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function createProcess($command)
    {
        return new Process(explode(' ', $command));
    }
}
