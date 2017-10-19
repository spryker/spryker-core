<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Queue\Model\Proxy\QueueProxy;

/**
 * @method \Spryker\Client\Queue\QueueConfig getConfig()
 */
class QueueFactory extends AbstractFactory
{
    /**
     * @var \Spryker\Client\Queue\Model\Proxy\QueueProxyInterface
     */
    protected static $queueProxy;

    /**
     * @return \Spryker\Client\Queue\Model\Proxy\QueueProxyInterface
     */
    public function createQueueProxy()
    {
        if (static::$queueProxy === null) {
            static::$queueProxy = new QueueProxy(
                $this->getQueueAdapters(),
                $this->getConfig()->getQueueAdapterConfiguration(),
                $this->getConfig()->getDefaultQueueAdapterConfiguration()
            );
        }

        return static::$queueProxy;
    }

    /**
     * @return \Spryker\Client\Queue\Model\Adapter\AdapterInterface[]
     */
    protected function getQueueAdapters()
    {
        return $this->getProvidedDependency(QueueDependencyProvider::QUEUE_ADAPTERS);
    }
}
