<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Reader;

use Spryker\Shared\Queue\QueueConfig as SharedQueueConfig;
use Spryker\Zed\Queue\QueueConfig;

class QueueConfigReader implements QueueConfigReaderInterface
{
    /**
     * @var \Spryker\Zed\Queue\QueueConfig
     */
    protected QueueConfig $queueConfig;

    /**
     * @param \Spryker\Zed\Queue\QueueConfig $queueConfig
     */
    public function __construct(QueueConfig $queueConfig)
    {
        $this->queueConfig = $queueConfig;
    }

    /**
     * @param string $queueName
     *
     * @return int
     */
    public function getMaxQueueWorkerByQueueName(string $queueName): int
    {
        $adapterConfiguration = $this->queueConfig->getQueueAdapterConfiguration();
        if (!$adapterConfiguration || !array_key_exists($queueName, $adapterConfiguration)) {
            $adapterDefaultConfiguration = $this->queueConfig->getDefaultQueueAdapterConfiguration();
            $adapterConfiguration = $adapterDefaultConfiguration ? [$queueName => $adapterDefaultConfiguration] : [];
        }

        $queueAdapterConfiguration = $adapterConfiguration[$queueName] ?? [];
        if (array_key_exists(SharedQueueConfig::CONFIG_MAX_WORKER_NUMBER, $queueAdapterConfiguration)) {
            return $queueAdapterConfiguration[SharedQueueConfig::CONFIG_MAX_WORKER_NUMBER];
        }

        return QueueConfig::DEFAULT_MAX_QUEUE_WORKER;
    }
}
