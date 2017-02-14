<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\Model\Adapter\AdapterInterface;

class QueueDependencyProvider extends AbstractDependencyProvider
{

    const QUEUE_ADAPTER = 'adapter queue';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container[static::QUEUE_ADAPTER] = function (Container $container) {
            return $this->createQueueAdapter($container);
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return AdapterInterface
     */
    protected function createQueueAdapter(Container $container)
    {
    }
}
