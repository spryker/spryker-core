<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue;

use Spryker\Shared\Queue\QueueConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class QueueConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const DEFAULT_QUEUE_OUTPUT_FILE_NAME = 'queue.log';

    /**
     * @var int
     */
    public const DEFAULT_INTERVAL_MILLISECONDS = 1000;

    /**
     * @var int
     */
    public const DEFAULT_PROCESS_TRIGGER_INTERVAL_MICROSECONDS = 1000;

    /**
     * @var int
     */
    public const DEFAULT_THRESHOLD = 59;

    /**
     * Specification:
     * - The threshold for the memory usage of the queue task.
     * - If the memory usage exceeds the threshold, the task throws a warning.
     * - The threshold is defined as a fraction of the total available memory per worker.
     *
     * @api
     *
     * @var float
     */
    public const QUEUE_TASK_MEMORY_USAGE_THRESHOLD = 0.75;

    /**
     * Specification:
     * - The default maximum number of workers that can be started for the given queue.
     *
     * @api
     *
     * @var int
     */
    public const DEFAULT_MAX_QUEUE_WORKER = 1;

    /**
     * @uses \SIGINT
     *
     * @var int
     */
    protected const SIGINT = 2;

    /**
     * @uses \SIGQUIT
     *
     * @var int
     */
    protected const SIGQUIT = 3;

    /**
     * @uses \SIGABRT
     *
     * @var int
     */
    protected const SIGABRT = 6;

    /**
     * @uses \SIGTERM
     *
     * @var int
     */
    protected const SIGTERM = 15;

    /**
     * @var int
     */
    protected const DEFAULT_QUEUE_TASK_MEMORY_CHUNK_SIZE = 1024;

    /**
     * @var int
     */
    protected const DEFAULT_QUEUE_TASK_MEMORY_SIZE = 1024;

    /**
     * @api
     *
     * @return array<string, mixed>|null
     */
    public function getWorkerMessageCheckOption()
    {
        $messageCheckOption = $this->getMessageCheckOptions();

        if (array_key_exists(QueueConstants::QUEUE_WORKER_MESSAGE_CHECK_OPTION, $this->getMessageCheckOptions())) {
            return $messageCheckOption[QueueConstants::QUEUE_WORKER_MESSAGE_CHECK_OPTION];
        }

        return null;
    }

    /**
     * @api
     *
     * @param string $queueName
     *
     * @return array<string, mixed>|null
     */
    public function getQueueReceiverOption($queueName)
    {
        $queueReceiverOptions = $this->getQueueReceiverOptions();

        if (isset($queueReceiverOptions[$queueName])) {
            return $queueReceiverOptions[$queueName];
        }

        if (array_key_exists(QueueConstants::QUEUE_DEFAULT_RECEIVER, $queueReceiverOptions)) {
            return $queueReceiverOptions[QueueConstants::QUEUE_DEFAULT_RECEIVER];
        }

        return null;
    }

    /**
     * Queue receiver options can be defined
     * here by having queue name as a key.
     *
     *  e.g: 'mail' => $option
     *
     * @return array<string, mixed>
     */
    protected function getQueueReceiverOptions()
    {
        return [];
    }

    /**
     * Queue check options can be defined
     *
     * @return array<string, mixed>
     */
    protected function getMessageCheckOptions()
    {
        return [];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getQueueServerId()
    {
        $defaultServerId = (gethostname()) ?: php_uname('n');

        return $this->get(QueueConstants::QUEUE_SERVER_ID, $defaultServerId);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getQueueWorkerInterval()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_INTERVAL_MILLISECONDS, static::DEFAULT_INTERVAL_MILLISECONDS);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getQueueProcessTriggerInterval()
    {
        return $this->get(QueueConstants::QUEUE_PROCESS_TRIGGER_INTERVAL_MICROSECONDS, static::DEFAULT_PROCESS_TRIGGER_INTERVAL_MICROSECONDS);
    }

    /**
     * @api
     *
     * @return resource|string
     */
    public function getQueueWorkerOutputFileName()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_OUTPUT_FILE_NAME, static::DEFAULT_QUEUE_OUTPUT_FILE_NAME);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getQueueWorkerLogStatus()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_LOG_ACTIVE, false);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getQueueWorkerMaxThreshold()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_MAX_THRESHOLD_SECONDS, static::DEFAULT_THRESHOLD);
    }

    /**
     * @api
     *
     * @return array<string, mixed>
     */
    public function getQueueAdapterConfiguration()
    {
        return $this->get(QueueConstants::QUEUE_ADAPTER_CONFIGURATION, []);
    }

    /**
     * @api
     *
     * @return array<string, mixed>
     */
    public function getDefaultQueueAdapterConfiguration(): array
    {
        return $this->get(QueueConstants::QUEUE_ADAPTER_CONFIGURATION_DEFAULT, []);
    }

    /**
     * @api
     *
     * @deprecated Use `vendor/bin/console queue:worker:start --stop-only-when-empty` instead.
     *
     * @return bool
     */
    public function getIsWorkerLoopEnabled(): bool
    {
        return $this->get(QueueConstants::QUEUE_WORKER_LOOP, false);
    }

    /**
     * Specification:
     * - Defines the list of signals that will be handled for the graceful worker shutdown on Unix platforms.
     *
     * @api
     *
     * @example
     * [
     *  static::SIGINT,
     *  static::SIGQUIT,
     *  static::SIGABRT,
     *  static::SIGTERM,
     * ]
     *
     * @return array<int>
     */
    public function getSignalsForGracefulWorkerShutdown(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns a map that associates the queue name with the number of messages to be fetched from that queue in a single chunk.
     *
     * @api
     *
     * @return array<string, int>
     */
    public function getQueueMessageChunkSizeMap(): array
    {
        return $this->get(QueueConstants::QUEUE_MESSAGE_CHUNK_SIZE_MAP, []);
    }

    /**
     * Specification:
     * - Recommended (optimal) memory of queue task chunk size for event message processing in KB.
     * - Used to log a warning if the task chunk data size exceeds this limit.
     *
     * @api
     *
     * @return int
     */
    public function getMaxQueueTaskMemoryChunkSize(): int
    {
        return $this->get(QueueConstants::MAX_QUEUE_TASK_MEMORY_CHUNK_SIZE, static::DEFAULT_QUEUE_TASK_MEMORY_CHUNK_SIZE);
    }

    /**
     * Specification:
     * - Recommended (optimal) memory limit for the entire queue task process in MB.
     * - Used to log a warning if the task exceeds this memory limit.
     * - The memory value should be chosen based on the total scheduler memory and the count of workers.
     *
     * @api
     *
     * @return int
     */
    public function getMaxQueueTaskMemorySize(): int
    {
        return $this->get(QueueConstants::MAX_QUEUE_TASK_MEMORY_SIZE, static::DEFAULT_QUEUE_TASK_MEMORY_SIZE);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isQueueWorkerWaitLimitEnabled(): bool
    {
        return $this->get(QueueConstants::QUEUE_WORKER_WAIT_LIMIT_ENABLED, false);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getQueueWorkerMaxWaitingSeconds(): int
    {
        return $this->get(QueueConstants::QUEUE_WORKER_MAX_WAITING_SECONDS, 30);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getQueueWorkerMaxWaitingRounds(): int
    {
        return $this->get(QueueConstants::QUEUE_WORKER_MAX_WAITING_ROUNDS, 3);
    }
}
