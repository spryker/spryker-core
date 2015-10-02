<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\System;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class SystemDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_HEARTBEAT = 'heartbeat facade';

    /**
     * @var Container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_HEARTBEAT] = function (Container $container) {
            return $container->getLocator()->heartbeat()->facade();
        };

        return $container;
    }

}
