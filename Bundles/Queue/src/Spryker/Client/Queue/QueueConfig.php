<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Queue\QueueConstants;

class QueueConfig extends AbstractBundleConfig
{
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
    public function getDefaultQueueAdapterConfiguration()
    {
        return $this->get(QueueConstants::QUEUE_ADAPTER_CONFIGURATION_DEFAULT, []);
    }
}
