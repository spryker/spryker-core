<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Communication;

use Spryker\Shared\Queue\QueueConstants;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Queue\QueueConfig;

/**
 * @method QueueConfig getConfig()
 */
class QueueCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return array
     */
    public function getQueueWorkerConfigs()
    {
        return [
            QueueConstants::QUEUE_WORKER_PROCESSOR => $this->getConfig()->getQueueWorkerProcessorCount(),
            QueueConstants::QUEUE_WORKER_INTERVAL_SECONDS => $this->getConfig()->getQueueWorkerInterval(),
            QueueConstants::QUEUE_WORKER_MAX_THRESHOLD_SECONDS => $this->getConfig()->getQueueWorkerMaxThreshold(),
            QueueConstants::QUEUE_WORKER_OUTPUT_FILE => $this->getConfig()->getQueueWorkerOutputFile(),
        ];
    }
}
