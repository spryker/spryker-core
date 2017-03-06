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

    const DEFAULT_QUEUE_WORKER_PROCESSOR = self::DEFAULT_INTERVAL_SECONDS;
    const DEFAULT_QUEUE_OUTPUT_FILE = 'queue.out';
    const DEFAULT_INTERVAL_SECONDS = 5;
    const DEFAULT_THRESHOLD = 55;

    /**
     * @param string $queueName
     *
     * @return QueueOptionTransfer
     */
    public function getReceiverConfig($queueName)
    {
        $queueReceiverConfigs = $this->getReceiverConfigs();

        if (array_key_exists($queueName, $queueReceiverConfigs)) {
            return $queueReceiverConfigs[$queueName];
        }

        return $queueReceiverConfigs['default'];
    }

    /**
     * @return array
     */
    protected function getReceiverConfigs()
    {
        return [
            'default' => $this->getDefaultReceiverConfig()
        ];
    }

    /**
     * @return QueueOptionTransfer
     */
    protected function getDefaultReceiverConfig()
    {
    }

    /**
     * @return int
     */
    public function getQueueWorkerProcessorCount()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_PROCESSOR, self::DEFAULT_QUEUE_WORKER_PROCESSOR);
    }

    /**
     * @return int
     */
    public function getQueueWorkerInterval()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_INTERVAL_SECONDS, self::DEFAULT_INTERVAL_SECONDS);
    }

    /**
     * @return string
     */
    public function getQueueWorkerOutputFile()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_OUTPUT_FILE, self::DEFAULT_QUEUE_OUTPUT_FILE);
    }

    /**
     * @return int
     */
    public function getQueueWorkerMaxThreshold()
    {
        return $this->get(QueueConstants::QUEUE_WORKER_MAX_THRESHOLD_SECONDS, self::DEFAULT_THRESHOLD);
    }
}
