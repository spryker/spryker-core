<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Queue\Model\Adapter\AdapterInterface;
use Spryker\Client\Queue\Model\Proxy\QueueProxy;
use Spryker\Client\Queue\Model\Proxy\QueueProxyInterface;

class QueueFactory extends AbstractFactory
{

    /**
     * @return QueueProxyInterface
     */
    public function createQueueProxy()
    {
        return new QueueProxy($this->getQueueAdapter());
    }

    /**
     * @return AdapterInterface
     */
    protected function getQueueAdapter()
    {
        return $this->getProvidedDependency(QueueDependencyProvider::QUEUE_ADAPTER);
    }
}
