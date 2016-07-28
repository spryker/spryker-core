<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder;

use Spryker\Zed\FactFinder\Dependency\Facade\FactFinderToCollectorBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class FactFinderDependencyProvider extends AbstractBundleDependencyProvider
{

    const COLLECTOR_FACADE = 'collector facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::COLLECTOR_FACADE] = function (Container $container) {
            return new FactFinderToCollectorBridge($container->getLocator()->collector()->facade());
        };

        return $container;
    }

}
