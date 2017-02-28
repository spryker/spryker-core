<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorInterface;

class QueueDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_QUEUE = 'queue client';
    const CLIENT_MESSAGE_PROCESSOR_PLUGIN = 'queue message processor plugin';

    /**
     * @param Container $container
     *
     * @return void
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::CLIENT_QUEUE] = function (Container $container) {
            return $container->getLocator()->queue()->client();
        };

        $container[self::CLIENT_MESSAGE_PROCESSOR_PLUGIN] = function (Container $container) {
            return $this->getProcessorMessagePlugins($container);
        };
    }

    /**
     * @param Container $container
     *
     * @return QueueMessageProcessorInterface[]
     */
    protected function getProcessorMessagePlugins(Container $container)
    {
        return [];
    }
}
