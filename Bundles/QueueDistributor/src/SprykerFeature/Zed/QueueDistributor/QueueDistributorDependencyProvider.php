<?php

namespace SprykerFeature\Zed\QueueDistributor;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class QueueDistributorDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_QUEUE = 'facade queue';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {

        $container[self::FACADE_QUEUE] = function (Container $container) {
            return $container->getLocator()->queue()->facade();
        };

        return $container;
    }
}
