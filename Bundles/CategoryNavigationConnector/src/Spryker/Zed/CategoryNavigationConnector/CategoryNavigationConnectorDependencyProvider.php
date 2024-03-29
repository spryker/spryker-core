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

/**
 * @method \Spryker\Zed\CategoryNavigationConnector\CategoryNavigationConnectorConfig getConfig()
 */
class CategoryNavigationConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_NAVIGATION = 'FACADE_NAVIGATION';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_NAVIGATION = 'QUERY_CONTAINER_NAVIGATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addCategoryQueryContainer($container);
        $this->addNavigationQueryContainer($container);
        $this->addNavigationFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCategoryQueryContainer(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_CATEGORY, function (Container $container) {
            return new CategoryNavigationConnectorToCategoryQueryContainerBridge($container->getLocator()->category()->queryContainer());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addNavigationQueryContainer(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_NAVIGATION, function (Container $container) {
            return new CategoryNavigationConnectorToNavigationQueryContainerBridge($container->getLocator()->navigation()->queryContainer());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addNavigationFacade(Container $container)
    {
        $container->set(static::FACADE_NAVIGATION, function (Container $container) {
            return new CategoryNavigationConnectorToNavigationFacadeBridge($container->getLocator()->navigation()->facade());
        });
    }
}
