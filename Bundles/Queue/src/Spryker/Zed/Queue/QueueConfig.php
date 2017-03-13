<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue;

use Generated\Shared\Transfer\QueueOptionTransfer;
use Spryker\Shared\Queue\QueueConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class QueueConfig extends AbstractBundleConfig
{

    const DEFAULT_QUEUE_OUTPUT_FILE = 'queue.log';
    const DEFAULT_INTERVAL_SECONDS = 1000;
    const DEFAULT_THRESHOLD = 59;

    /**
     * @param string $queueName
     *
     * @return \Generated\Shared\Transfer\QueueOptionTransfer
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
            QueueConstants::QUEUE_WORKER_INTERVAL_MILLISECONDS => $this->getQueueWorkerInterval(),
            QueueConstants::QUEUE_WORKER_OUTPUT_FILE => $this->getQueueWorkerOutputFile(),
            QueueConstants::QUEUE_WORKER_MAX_THRESHOLD_SECONDS => $this->getQueueWorkerMaxThreshold(),
        ];
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
     * The Amount of queue processors can be defined
     * here by having queue name as a key.
     * The default value is 1 process per queue.
     *
     *  e.g: 'mail' => 5
     *
     * @return int[]
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
        return $this->get(QueueConstants::QUEUE_WORKER_INTERVAL_MILLISECONDS, self::DEFAULT_INTERVAL_SECONDS);
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
            'default' => $this->getDefaultQueueReceiverConfig(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\QueueOptionTransfer
     */
    protected function getDefaultQueueReceiverConfig()
    {
        return new QueueOptionTransfer();
    }

}
