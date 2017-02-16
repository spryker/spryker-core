<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Queue\Model\Proxy\QueueProxy;

class QueueFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Queue\Model\Proxy\QueueProxyInterface
     */
    public function createQueueProxy()
    {
        return new QueueProxy($this->getQueueAdapter());
    }

    /**
     * @return \Spryker\Client\Queue\Model\Adapter\AdapterInterface
     */
    protected function getQueueAdapter()
    {
        return $this->getProvidedDependency(QueueDependencyProvider::QUEUE_ADAPTER);
    }

}
