<?php

namespace SprykerFeature\Zed\QueueDistributor;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class QueueDistributorDependencyProvider extends AbstractBundleDependencyProvider
{

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        return $container;
    }
}
