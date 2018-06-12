<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization;

use Spryker\Shared\Queue\QueueConfig;
use Spryker\Shared\Queue\QueueConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SynchronizationConfig extends AbstractBundleConfig
{
    const DEFAULT_SYNC_STORAGE_QUEUE_MESSAGE_CHUNK_SIZE = 10000;
    const DEFAULT_SYNC_SEARCH_QUEUE_MESSAGE_CHUNK_SIZE = 10000;
    const EXPORT_MESSAGE_CHUNK_SIZE = 100;

    /**
     * @param string $queueName
     *
     * @return int
     */
    public function getQueueWorkerNumber($queueName)
    {
        $numberOfWorker = 1;
        $queueAdapterConfigs = $this->get(QueueConstants::QUEUE_ADAPTER_CONFIGURATION);

        if (!isset($queueAdapterConfigs[$queueName])) {
            return $numberOfWorker;
        }

        $queueAdapterConfig = $queueAdapterConfigs[$queueName];
        if (isset($queueAdapterConfig[QueueConfig::CONFIG_MAX_WORKER_NUMBER])) {
            $numberOfWorker = $queueAdapterConfig[QueueConfig::CONFIG_MAX_WORKER_NUMBER];
        }

        return $numberOfWorker;
    }

    /**
     * @return int
     */
    public function getSyncStorageQueueMessageChunkSize()
    {
        return static::DEFAULT_SYNC_STORAGE_QUEUE_MESSAGE_CHUNK_SIZE;
    }

    /**
     * @return int
     */
    public function getSyncSearchQueueMessageChunkSize()
    {
        return static::DEFAULT_SYNC_SEARCH_QUEUE_MESSAGE_CHUNK_SIZE;
    }

    /**
     * @return int
     */
    public function getSyncExportChunkSize()
    {
        return static::EXPORT_MESSAGE_CHUNK_SIZE;
    }
}
