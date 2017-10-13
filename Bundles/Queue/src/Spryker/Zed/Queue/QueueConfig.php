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
    const DEFAULT_QUEUE_OUTPUT_FILE_NAME = 'queue.log';
    const DEFAULT_INTERVAL_SECONDS = 1000;
    const DEFAULT_THRESHOLD = 59;

    /**
     * @param string $queueName
     *
     * @return array|null
     */
    public function getQueueReceiverOption($queueName)
    {
        $queueReceiverOptions = $this->getQueueReceiverOptions();

        if (array_key_exists($queueName, $queueReceiverOptions)) {
            return $queueReceiverOptions[$queueName];
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
        return $this->get(QueueConstants::QUEUE_WORKER_INTERVAL_MILLISECONDS, self::DEFAULT_INTERVAL_SECONDS);
    }

    /**
     * @return string
     */
    public function getQueueWorkerOutputFileName()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_OUTPUT_FILE_NAME, self::DEFAULT_QUEUE_OUTPUT_FILE_NAME);
    }

    /**
     * @return int
     */
    public function getQueueWorkerMaxThreshold()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_MAX_THRESHOLD_SECONDS, self::DEFAULT_THRESHOLD);
    }

    /**
     * @return array
     */
    public function getQueueAdapterConfiguration()
    {
        return $this->get(QueueConstants::QUEUE_ADAPTER_CONFIGURATION);
    }
}
