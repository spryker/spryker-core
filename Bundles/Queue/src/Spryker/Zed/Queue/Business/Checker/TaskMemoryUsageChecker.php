<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Checker;

use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Queue\Business\Reader\QueueConfigReaderInterface;
use Spryker\Zed\Queue\QueueConfig;

class TaskMemoryUsageChecker implements TaskMemoryUsageCheckerInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\Queue\QueueConfig
     */
    protected QueueConfig $queueConfig;

    /**
     * @var \Spryker\Zed\Queue\Business\Reader\QueueConfigReaderInterface
     */
    protected QueueConfigReaderInterface $queueConfigReader;

    /**
     * @param \Spryker\Zed\Queue\QueueConfig $queueConfig
     * @param \Spryker\Zed\Queue\Business\Reader\QueueConfigReaderInterface $queueConfigReader
     */
    public function __construct(QueueConfig $queueConfig, QueueConfigReaderInterface $queueConfigReader)
    {
        $this->queueConfig = $queueConfig;
        $this->queueConfigReader = $queueConfigReader;
    }

    /**
     * @param string $queueName
     * @param list<\Generated\Shared\Transfer\QueueReceiveMessageTransfer> $messages
     * @param int $chunkSize
     *
     * @return void
     */
    public function check(string $queueName, array $messages, int $chunkSize): void
    {
        $this->checkQueueTaskMemoryChunkSize($queueName, $messages, $chunkSize);
        $this->checkQueueTaskMemory($queueName);
    }

    /**
     * @param string $queueName
     * @param list<\Generated\Shared\Transfer\QueueReceiveMessageTransfer> $messages
     * @param int $chunkSize
     *
     * @return void
     */
    protected function checkQueueTaskMemoryChunkSize(string $queueName, array $messages, int $chunkSize): void
    {
        $totalChunkSize = 0;
        foreach ($messages as $queueReceiveMessageTransfer) {
            if ($queueReceiveMessageTransfer->getQueueMessage()) {
                $totalChunkSize += strlen($queueReceiveMessageTransfer->getQueueMessageOrFail()->getBody());
            }
        }

        $totalChunkSizeInKB = $totalChunkSize / 1024;
        if ($totalChunkSizeInKB > $this->queueConfig->getMaxQueueTaskMemoryChunkSize()) {
            $this->getLogger()->warning(sprintf(
                'Queue \'%s\' task chunk size (%d) has a total size of %.2f KB, which exceeds the recommended limit of %d KB. Please reduce the chunk or message size.',
                $queueName,
                $chunkSize,
                $totalChunkSizeInKB,
                $this->queueConfig->getMaxQueueTaskMemoryChunkSize(),
            ));
        }
    }

    /**
     * @param string $queueName
     *
     * @return void
     */
    protected function checkQueueTaskMemory(string $queueName): void
    {
        $maxQueueWorkerCount = $this->queueConfigReader->getMaxQueueWorkerByQueueName($queueName);
        $maxQueueTaskMemorySize = $this->queueConfig->getMaxQueueTaskMemorySize();

        $maxQueueTaskMemorySizeInBytes = $maxQueueTaskMemorySize * 1024 * 1024;
        $availableQueueTaskMemoryPerWorker = $maxQueueTaskMemorySizeInBytes / $maxQueueWorkerCount;

        $currentQueueTaskMemoryUsageInBytes = memory_get_usage(true);
        if ($currentQueueTaskMemoryUsageInBytes > QueueConfig::QUEUE_TASK_MEMORY_USAGE_THRESHOLD * $availableQueueTaskMemoryPerWorker) {
            $this->getLogger()->warning(sprintf(
                'Queue \'%s\' task memory usage is %.2f MB, which exceeds %.0f%% of the available memory per worker (%d MB). Please reduce the memory usage or the number of concurrent task workers, or it might disrupt the process.',
                $queueName,
                $currentQueueTaskMemoryUsageInBytes / (1024 * 1024),
                QueueConfig::QUEUE_TASK_MEMORY_USAGE_THRESHOLD * 100,
                $availableQueueTaskMemoryPerWorker / (1024 * 1024),
            ));
        }
    }
}
