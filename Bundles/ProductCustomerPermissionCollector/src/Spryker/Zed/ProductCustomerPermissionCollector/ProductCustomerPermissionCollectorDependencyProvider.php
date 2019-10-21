<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermissionCollector;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCustomerPermissionCollector\Dependency\Facade\ProductCustomerPermissionCollectorToCollectorFacadeBridge;
use Spryker\Zed\ProductCustomerPermissionCollector\Dependency\Facade\ProductCustomerPermissionCollectorToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\ProductCustomerPermissionCollector\ProductCustomerPermissionCollectorConfig getConfig()
 */
class ProductCustomerPermissionCollectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COLLECTOR = 'FACADE_COLLECTOR';

    public const FACADE_STORE = 'FACADE_STORE';

    public const SERVICE_DATA_READER = 'SERVICE_DATA_READER';

    public const QUERY_CONTAINER_TOUCH = 'QUERY_CONTAINER_TOUCH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $this->addCollectorFacade($container);
        $this->addDataReaderService($container);
        $this->addStoreFacade($container);
        $this->addTouchQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCollectorFacade(Container $container): Container
    {
        $container[static::FACADE_COLLECTOR] = function (Container $container) {
            return new ProductCustomerPermissionCollectorToCollectorFacadeBridge($container->getLocator()->collector()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataReaderService(Container $container): Container
    {
        $container[static::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new ProductCustomerPermissionCollectorToStoreFacadeBridge(Store::getInstance());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchQueryContainer(Container $container): Container
    {
        $container[static::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };

        return $container;
    }
}
