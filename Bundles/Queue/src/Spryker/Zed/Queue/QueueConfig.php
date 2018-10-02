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
    public const DEFAULT_QUEUE_OUTPUT_FILE_NAME = 'queue.log';
    public const DEFAULT_INTERVAL_MILLISECONDS = 1000;
    public const DEFAULT_PROCESS_TRIGGER_INTERVAL_MICROSECONDS = 1000;
    public const DEFAULT_THRESHOLD = 59;

    /**
     * @return array|null
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
     * @param string $queueName
     *
     * @return array|null
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
     * @return array
     */
    protected function getQueueReceiverOptions()
    {
        return [];
    }

    /**
     * Queue check options can be defined
     *
     * @return array
     */
    protected function getMessageCheckOptions()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getQueueServerId()
    {
        $defaultServerId = (gethostname()) ?: php_uname('n');

        return $this->get(QueueConstants::QUEUE_SERVER_ID, $defaultServerId);
    }

    /**
     * @return int
     */
    public function getQueueWorkerInterval()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_INTERVAL_MILLISECONDS, static::DEFAULT_INTERVAL_MILLISECONDS);
    }

    /**
     * @return int
     */
    public function getQueueProcessTriggerInterval()
    {
        return $this->get(QueueConstants::QUEUE_PROCESS_TRIGGER_INTERVAL_MICROSECONDS, static::DEFAULT_PROCESS_TRIGGER_INTERVAL_MICROSECONDS);
    }

    /**
     * @return string
     */
    public function getQueueWorkerOutputFileName()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_OUTPUT_FILE_NAME, static::DEFAULT_QUEUE_OUTPUT_FILE_NAME);
    }

    /**
     * @return string
     */
    public function getQueueWorkerLogStatus()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_LOG_ACTIVE, false);
    }

    /**
     * @return int
     */
    public function getQueueWorkerMaxThreshold()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_MAX_THRESHOLD_SECONDS, static::DEFAULT_THRESHOLD);
    }

    /**
     * @return array
     */
    public function getQueueAdapterConfiguration()
    {
        return $this->get(QueueConstants::QUEUE_ADAPTER_CONFIGURATION, []);
    }

    /**
     * @return array
     */
    public function getDefaultQueueAdapterConfiguration(): array
    {
        return $this->get(QueueConstants::QUEUE_ADAPTER_CONFIGURATION_DEFAULT, []);
    }

    /**
     * @return bool
     */
    public function getIsWorkerLoopEnabled(): bool
    {
        return $this->get(QueueConstants::QUEUE_WORKER_LOOP, false);
    }
}
