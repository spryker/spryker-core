<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToPriceBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToProductOptionBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToTaxBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlBridge;

class ProductDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_LOCALE = 'FACADE_LOCALE';
    const FACADE_PRICE = 'FACADE_PRICE';
    const FACADE_PRODUCT_CATEGORY = 'FACADE_PRODUCT_CATEGORY';
    const FACADE_PRODUCT_OPTION = 'FACADE_PRODUCT_OPTION';
    const FACADE_TAX = 'FACADE_TAX';
    const FACADE_URL = 'FACADE_URL';
    const FACADE_TOUCH = 'FACADE_TOUCH';

    const QUERY_CONTAINER_PRODUCT_CATEGORY = 'QUERY_CONTAINER_PRODUCT_CATEGORY';

    const PRODUCT_ABSTRACT_PLUGINS_CREATE = 'PRODUCT_ABSTRACT_PLUGINS_CREATE';
    const PRODUCT_ABSTRACT_PLUGINS_UPDATE = 'PRODUCT_ABSTRACT_PLUGINS_UPDATE';
    const PRODUCT_ABSTRACT_PLUGINS_READ = 'PRODUCT_ABSTRACT_PLUGINS_READ';

    const PRODUCT_CONCRETE_PLUGINS_CREATE = 'PRODUCT_CONCRETE_PLUGINS_CREATE';
    const PRODUCT_CONCRETE_PLUGINS_UPDATE = 'PRODUCT_CONCRETE_PLUGINS_UPDATE';
    const PRODUCT_CONCRETE_PLUGINS_READ = 'PRODUCT_CONCRETE_PLUGINS_READ';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return new ProductToUrlBridge($container->getLocator()->url()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new ProductToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_TAX] = function (Container $container) {
            return new ProductToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[self::FACADE_PRICE] = function (Container $container) {
            return new ProductToPriceBridge($container->getLocator()->price()->facade());
        };

        $container[self::PRODUCT_ABSTRACT_PLUGINS_CREATE] = function (Container $container) {
            return $this->getProductAbstractCreatePlugins($container);
        };

        $container[self::PRODUCT_ABSTRACT_PLUGINS_READ] = function (Container $container) {
            return $this->getProductAbstractReadPlugins($container);
        };

        $container[self::PRODUCT_ABSTRACT_PLUGINS_UPDATE] = function (Container $container) {
            return $this->getProductAbstractUpdatePlugins($container);
        };

        $container[self::PRODUCT_CONCRETE_PLUGINS_CREATE] = function (Container $container) {
            return $this->getProductConcreteCreatePlugins($container);
        };

        $container[self::PRODUCT_CONCRETE_PLUGINS_READ] = function (Container $container) {
            return $this->getProductConcreteReadPlugins($container);
        };

        $container[self::PRODUCT_CONCRETE_PLUGINS_UPDATE] = function (Container $container) {
            return $this->getProductConcreteUpdatePlugins($container);
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
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_PRODUCT_OPTION] = function (Container $container) {
            return new ProductToProductOptionBridge($container->getLocator()->productOption()->facade());
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return new ProductToUrlBridge($container->getLocator()->url()->facade());
        };

        $container[self::QUERY_CONTAINER_PRODUCT_CATEGORY] = function (Container $container) {
            return $container->getLocator()->productCategory()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected function getProductAbstractCreatePlugins(Container $container)
    {
        return [

        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected function getProductAbstractReadPlugins(Container $container)
    {
        return [

        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface[]
     */
    protected function getProductAbstractUpdatePlugins(Container $container)
    {
        return [

        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected function getProductConcreteCreatePlugins(Container $container)
    {
        return [
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected function getProductConcreteReadPlugins(Container $container)
    {
        return [
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface[]
     */
    protected function getProductConcreteUpdatePlugins(Container $container)
    {
        return [
        ];
    }

}
