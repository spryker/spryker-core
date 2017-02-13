<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Queue\Model\AdapterProxy;
use Spryker\Client\Queue\Model\AdapterProxyInterface;

class QueueFactory extends AbstractFactory
{

    /**
     * @return AdapterProxyInterface
     */
    public function createAdapterProxy()
    {
        return new AdapterProxy($this->getQueueAdapter());
    }

    /**
     * @return mixed
     */
    protected function getQueueAdapter()
    {
        return $this->getProvidedDependency(QueueDependencyProvider::ADAPTER_QUEUE);
    }
}
