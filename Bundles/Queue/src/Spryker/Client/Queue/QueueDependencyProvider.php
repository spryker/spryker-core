<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\Dependency\Adapter\QueueAdapterInterface;

class QueueDependencyProvider extends AbstractDependencyProvider
{

    const ADAPTER_QUEUE = 'queue adapter';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container[static::ADAPTER_QUEUE] = function (Container $container) {
            return $this->getQueueAdapter($container);
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return QueueAdapterInterface
     */
    protected function getQueueAdapter(Container $container)
    {
    }
}
