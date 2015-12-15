<?php

namespace Spryker\Zed\Queue;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class QueueDependencyProvider extends AbstractBundleDependencyProvider
{

    const WORKER_TASKS = 'worker tasks';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::WORKER_TASKS] = function (Container $container) {
            return [];
        };

        return $container;
    }

}
