<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToProductBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxBridge;

class ProductOptionDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_LOCALE = 'LOCALE_FACADE';
    const FACADE_TAX = 'TAX_FACADE';
    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new ProductOptionToProductBridge($container->getLocator()->product()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductOptionToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_TAX] = function (Container $container) {
            return new ProductOptionToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[self::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
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
            return new ProductOptionToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

}
