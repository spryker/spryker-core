<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Product\Dependency\Facade\ProductToEventBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlBridge;
use Spryker\Zed\Product\Dependency\QueryContainer\ProductToUrlBridge as ProductToUrlQueryContainerBridge;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingBridge;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilTextBridge;

class ProductDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_URL = 'FACADE_URL';
    public const FACADE_TOUCH = 'FACADE_TOUCH';
    public const FACADE_EVENT = 'FACADE_EVENT';

    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const QUERY_CONTAINER_URL = 'QUERY_CONTAINER_URL';

    public const PRODUCT_ABSTRACT_PLUGINS_BEFORE_CREATE = 'PRODUCT_ABSTRACT_PLUGINS_BEFORE_CREATE';
    public const PRODUCT_ABSTRACT_PLUGINS_BEFORE_UPDATE = 'PRODUCT_ABSTRACT_PLUGINS_BEFORE_UPDATE';
    public const PRODUCT_ABSTRACT_PLUGINS_READ = 'PRODUCT_ABSTRACT_PLUGINS_READ';
    public const PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE = 'PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE';
    public const PRODUCT_ABSTRACT_PLUGINS_AFTER_UPDATE = 'PRODUCT_ABSTRACT_PLUGINS_AFTER_UPDATE';

    public const PRODUCT_CONCRETE_PLUGINS_BEFORE_CREATE = 'PRODUCT_CONCRETE_PLUGINS_BEFORE_CREATE';
    public const PRODUCT_CONCRETE_PLUGINS_BEFORE_UPDATE = 'PRODUCT_CONCRETE_PLUGINS_BEFORE_UPDATE';
    public const PRODUCT_CONCRETE_PLUGINS_READ = 'PRODUCT_CONCRETE_PLUGINS_BEFORE_READ';
    public const PRODUCT_CONCRETE_PLUGINS_AFTER_CREATE = 'PRODUCT_CONCRETE_PLUGINS_AFTER_CREATE';
    public const PRODUCT_CONCRETE_PLUGINS_AFTER_UPDATE = 'PRODUCT_CONCRETE_PLUGINS_AFTER_UPDATE';

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

        $container[self::SERVICE_UTIL_TEXT] = function (Container $container) {
            return new ProductToUtilTextBridge($container->getLocator()->utilText()->service());
        };

        $container[self::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        $container[self::FACADE_EVENT] = function (Container $container) {
            return new ProductToEventBridge($container->getLocator()->event()->facade());
        };

        $container[self::PRODUCT_ABSTRACT_PLUGINS_BEFORE_CREATE] = function (Container $container) {
            return $this->getProductAbstractBeforeCreatePlugins($container);
        };

        $container[self::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE] = function (Container $container) {
            return $this->getProductAbstractAfterCreatePlugins($container);
        };

        $container[self::PRODUCT_ABSTRACT_PLUGINS_READ] = function (Container $container) {
            return $this->getProductAbstractReadPlugins($container);
        };

        $container[self::PRODUCT_ABSTRACT_PLUGINS_BEFORE_UPDATE] = function (Container $container) {
            return $this->getProductAbstractBeforeUpdatePlugins($container);
        };

        $container[self::PRODUCT_ABSTRACT_PLUGINS_AFTER_UPDATE] = function (Container $container) {
            return $this->getProductAbstractAfterUpdatePlugins($container);
        };

        $container[self::PRODUCT_CONCRETE_PLUGINS_BEFORE_CREATE] = function (Container $container) {
            return $this->getProductConcreteBeforeCreatePlugins($container);
        };

        $container[self::PRODUCT_CONCRETE_PLUGINS_AFTER_CREATE] = function (Container $container) {
            return $this->getProductConcreteAfterCreatePlugins($container);
        };

        $container[self::PRODUCT_CONCRETE_PLUGINS_READ] = function (Container $container) {
            return $this->getProductConcreteReadPlugins($container);
        };

        $container[self::PRODUCT_CONCRETE_PLUGINS_BEFORE_UPDATE] = function (Container $container) {
            return $this->getProductConcreteBeforeUpdatePlugins($container);
        };

        $container[self::PRODUCT_CONCRETE_PLUGINS_AFTER_UPDATE] = function (Container $container) {
            return $this->getProductConcreteAfterUpdatePlugins($container);
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
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[self::QUERY_CONTAINER_URL] = function (Container $container) {
            return new ProductToUrlQueryContainerBridge($container->getLocator()->url()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[]
     */
    protected function getProductAbstractBeforeCreatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[]
     */
    protected function getProductAbstractAfterCreatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface[]
     */
    protected function getProductAbstractReadPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[]
     */
    protected function getProductAbstractBeforeUpdatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[]
     */
    protected function getProductAbstractAfterUpdatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface[]
     */
    protected function getProductConcreteBeforeCreatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface[]
     */
    protected function getProductConcreteAfterCreatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface[]
     */
    protected function getProductConcreteReadPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[]
     */
    protected function getProductConcreteBeforeUpdatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[]
     */
    protected function getProductConcreteAfterUpdatePlugins(Container $container)
    {
        return [];
    }
}
