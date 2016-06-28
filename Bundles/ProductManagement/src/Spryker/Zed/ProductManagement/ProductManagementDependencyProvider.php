<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCategoryBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTouchBridge;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToUrlBridge;

class ProductManagementDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_CATEGORY = 'FACADE_LOCALE';
    const FACADE_LOCALE = 'FACADE_LOCALE';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_TOUCH = 'FACADE_TOUCH';
    const FACADE_URL = 'FACADE_URL';

    const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';


    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return new ProductManagementToCategoryBridge($container->getLocator()->category()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductManagementToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new ProductManagementToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return new ProductManagementToUrlBridge($container->getLocator()->url()->facade());
        };

        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return new ProductManagementToCategoryBridge($container->getLocator()->category()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductManagementToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new ProductManagementToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return new ProductManagementToUrlBridge($container->getLocator()->url()->facade());
        };

        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        };

        return $container;
    }

}
