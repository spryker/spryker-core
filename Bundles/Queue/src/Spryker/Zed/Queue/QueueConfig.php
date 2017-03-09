<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue;

use Generated\Shared\Transfer\QueueOptionTransfer;
use Spryker\Shared\Queue\QueueConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class QueueConfig extends AbstractBundleConfig
{

    const DEFAULT_QUEUE_OUTPUT_FILE = 'queue.out';
    const DEFAULT_INTERVAL_SECONDS = 5;
    const DEFAULT_THRESHOLD = 55;

    /**
     * @param string $queueName
     *
     * @return QueueOptionTransfer
     */
    public function getQueueReceiverConfig($queueName)
    {
        $queueReceiverConfigs = $this->getQueueReceiverConfigs();

        if (array_key_exists($queueName, $queueReceiverConfigs)) {
            return $queueReceiverConfigs[$queueName];
        }

        return $queueReceiverConfigs['default'];
    }

    /**
     * @return array
     */
    public function getQueueWorkerConfig()
    {
        return [
            QueueConstants::QUEUE_WORKER_PROCESSOR => $this->getQueueWorkerProcessorCount(),
            QueueConstants::QUEUE_WORKER_INTERVAL_SECONDS => $this->getQueueWorkerInterval(),
            QueueConstants::QUEUE_WORKER_OUTPUT_FILE => $this->getQueueWorkerOutputFile(),
            QueueConstants::QUEUE_WORKER_MAX_THRESHOLD_SECONDS => $this->getQueueWorkerMaxThreshold(),
        ];
    }

    /**
     * @return array
     */
    protected function getQueueWorkerProcessorCount()
    {
        return [];
    }

    /**
     * @return int
     */
    protected function getQueueWorkerInterval()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_INTERVAL_SECONDS, self::DEFAULT_INTERVAL_SECONDS);
    }

    /**
     * @return string
     */
    protected function getQueueWorkerOutputFile()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_OUTPUT_FILE, self::DEFAULT_QUEUE_OUTPUT_FILE);
    }

    /**
     * @return int
     */
    protected function getQueueWorkerMaxThreshold()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_MAX_THRESHOLD_SECONDS, self::DEFAULT_THRESHOLD);
    }

    /**
     * @return array
     */
    protected function getQueueReceiverConfigs()
    {
        return [
            'default' => $this->getDefaultQueueReceiverConfig()
        ];
    }

    /**
     * @return QueueOptionTransfer
     */
    protected function getDefaultQueueReceiverConfig()
    {
    }
}
