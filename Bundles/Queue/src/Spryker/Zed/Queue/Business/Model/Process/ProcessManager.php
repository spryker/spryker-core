<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Model\Process;

use Generated\Shared\Transfer\QueueProcessTransfer;
use Orm\Zed\Queue\Persistence\Map\SpyQueueProcessTableMap;
use Orm\Zed\Queue\Persistence\SpyQueueProcess;
use Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface;
use Symfony\Component\Process\Process;

class ProcessManager implements ProcessManagerInterface
{

    /**
     * @var QueueQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var string
     */
    protected $serverUniqueId;

    /**
     * @param QueueQueryContainerInterface $queryContainer
     * @param string $serverUniqueId
     */
    public function __construct(QueueQueryContainerInterface $queryContainer, $serverUniqueId)
    {
        $this->queryContainer = $queryContainer;
        $this->serverUniqueId = $serverUniqueId;
    }

    /**
     * @param string $queue
     * @param string $command
     *
     * @return Process
     */
    public function triggerQueueProcess($command, $queue)
    {
        $process = new Process($command);
        $process->start();

        $queueProcessTransfer = $this->createQueueProcessTransfer($queue, $process->getPid());
        $this->saveProcess($queueProcessTransfer);

        return $process;
    }

    /**
     * @param string $queueName
     *
     * @return int
     */
    public function getBusyProcessNumber($queueName)
    {
        $processIds = $this->queryContainer
            ->queryProcessesByServerIdAndQueueName($this->serverUniqueId, $queueName)
            ->select(SpyQueueProcessTableMap::COL_PROCESS_PID)
            ->find();

        $busyProcessIndex = $this->releaseIdleProcesses($processIds);

        return $busyProcessIndex;
    }

    /**
     * @return void
     */
    public function flushIdleProcesses()
    {
        $processIds = $this->queryContainer
            ->queryProcessesByServerId($this->serverUniqueId)
            ->select(SpyQueueProcessTableMap::COL_PROCESS_PID)
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
     * @param int $processId
     *
     * @return bool
     */
    protected function isProcessRunning($processId)
    {
        $output = exec(sprintf('ps -p %s | grep %s | grep -v \'<defunct>\'', $processId, $processId));

        return trim($output) !== '';
    }


    /**
     * @param string $queue
     * @param int $processId
     *
     * @return QueueProcessTransfer
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
     * @param QueueProcessTransfer $queueProcessTransfer
     *
     * @return QueueProcessTransfer
     */
    protected function saveProcess(QueueProcessTransfer $queueProcessTransfer)
    {
        $processEntity = new SpyQueueProcess();
        $processEntity->fromArray($queueProcessTransfer->toArray());
        $processEntity->save();

        return $this->convertSToQueueProcessTransfer($processEntity);
    }

    /**
     * @param SpyQueueProcess $processEntity
     *
     * @return QueueProcessTransfer
     */
    protected function convertSToQueueProcessTransfer(SpyQueueProcess $processEntity)
    {
        $queueProcessTransfer = new QueueProcessTransfer();
        $queueProcessTransfer->fromArray($processEntity->toArray(), true);

        return $queueProcessTransfer;
    }

    /**
     * @param $cleanupProcesses
     *
     * @return int
     */
    protected function deleteProcesses($cleanupProcesses)
    {
        return $this->queryContainer
            ->queryProcessesByProcessIds($cleanupProcesses)
            ->delete();
    }
}
