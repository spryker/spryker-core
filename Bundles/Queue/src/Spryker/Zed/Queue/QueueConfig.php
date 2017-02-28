<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue;

use Generated\Shared\Transfer\QueueOptionTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class QueueConfig extends AbstractBundleConfig
{

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
}
