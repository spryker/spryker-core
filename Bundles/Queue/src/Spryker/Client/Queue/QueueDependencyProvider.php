<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class QueueDependencyProvider extends AbstractDependencyProvider
{

    const QUEUE_ADAPTERS = 'queue adapters';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container[static::QUEUE_ADAPTERS] = function (Container $container) {
            return $this->createQueueAdapters($container);
        };

        return $container;
    }

    /**
     * All queue adapters need to define here as an array with key as queue adapter name.
     * Queue adapters need to implement: \Spryker\Client\Queue\Model\Adapter\AdapterInterface
     *
     * e.g: 'rabbitmq' => $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
     *
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Queue\Model\Adapter\AdapterInterface[]
     */
    protected function createQueueAdapters(Container $container)
    {
    }

}
