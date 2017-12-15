<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryNavigationConnector;

use Spryker\Zed\CategoryNavigationConnector\Dependency\Facade\CategoryNavigationConnectorToNavigationFacadeBridge;
use Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer\CategoryNavigationConnectorToCategoryQueryContainerBridge;
use Spryker\Zed\CategoryNavigationConnector\Dependency\QueryContainer\CategoryNavigationConnectorToNavigationQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CategoryNavigationConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_NAVIGATION = 'FACADE_NAVIGATION';
    const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    const QUERY_CONTAINER_NAVIGATION = 'QUERY_CONTAINER_NAVIGATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return new CategoryNavigationConnectorToCategoryQueryContainerBridge($container->getLocator()->category()->queryContainer());
        };

        $container[self::QUERY_CONTAINER_NAVIGATION] = function (Container $container) {
            return new CategoryNavigationConnectorToNavigationQueryContainerBridge($container->getLocator()->navigation()->queryContainer());
        };

        $container[self::FACADE_NAVIGATION] = function (Container $container) {
            return new CategoryNavigationConnectorToNavigationFacadeBridge($container->getLocator()->navigation()->facade());
        };

        return $container;
    }
}
