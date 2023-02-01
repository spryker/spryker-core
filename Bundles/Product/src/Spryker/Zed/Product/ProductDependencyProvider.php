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
use Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToStoreBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchBridge;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlBridge;
use Spryker\Zed\Product\Dependency\QueryContainer\ProductToUrlBridge as ProductToUrlQueryContainerBridge;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingBridge;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilTextBridge;

/**
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 */
class ProductDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_URL = 'FACADE_URL';

    /**
     * @var string
     */
    public const FACADE_TOUCH = 'FACADE_TOUCH';

    /**
     * @var string
     */
    public const FACADE_EVENT = 'FACADE_EVENT';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_URL = 'QUERY_CONTAINER_URL';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_PLUGINS_BEFORE_CREATE = 'PRODUCT_ABSTRACT_PLUGINS_BEFORE_CREATE';

    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_PLUGINS_BEFORE_UPDATE = 'PRODUCT_ABSTRACT_PLUGINS_BEFORE_UPDATE';

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_EXPANDER} instead.
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_PLUGINS_READ = 'PRODUCT_ABSTRACT_PLUGINS_READ';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_EXPANDER = 'PLUGINS_PRODUCT_ABSTRACT_EXPANDER';

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_POST_CREATE} instead.
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE = 'PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_POST_CREATE = 'PLUGINS_PRODUCT_ABSTRACT_POST_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_CONCRETE_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_EXPANDER';

    /**
     * @var string
     */
    public const PRODUCT_ABSTRACT_PLUGINS_AFTER_UPDATE = 'PRODUCT_ABSTRACT_PLUGINS_AFTER_UPDATE';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_PLUGINS_BEFORE_CREATE = 'PRODUCT_CONCRETE_PLUGINS_BEFORE_CREATE';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_PLUGINS_BEFORE_UPDATE = 'PRODUCT_CONCRETE_PLUGINS_BEFORE_UPDATE';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PRODUCT_CONCRETE_PLUGINS_READ = 'PRODUCT_CONCRETE_PLUGINS_BEFORE_READ';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_PLUGINS_AFTER_CREATE = 'PRODUCT_CONCRETE_PLUGINS_AFTER_CREATE';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_PLUGINS_AFTER_UPDATE = 'PRODUCT_CONCRETE_PLUGINS_AFTER_UPDATE';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_PRE_CREATE = 'PLUGINS_PRODUCT_ABSTRACT_PRE_CREATE';

    /**
     * @var string
     */
    public const FACADE_MESSAGE_BROKER = 'FACADE_MESSAGE_BROKER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_CONCRETE_MERGER = 'PLUGINS_PRODUCT_CONCRETE_MERGER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_COLLECTION_EXPANDER = 'PLUGINS_PRODUCT_ABSTRACT_COLLECTION_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addStoreFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addUrlFacade($container);
        $container = $this->addTouchFacade($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addEventFacade($container);
        $container = $this->addProductAbstractBeforeCreatePlugins($container);
        $container = $this->addProductAbstractAfterCreatePlugins($container);
        $container = $this->addProductAbstractPostCreatePlugins($container);
        $container = $this->addProductAbstractReadPlugins($container);
        $container = $this->addProductAbstractExpanderPlugins($container);
        $container = $this->addProductAbstractBeforeUpdatePlugin($container);
        $container = $this->addProductAbstractAfterUpdatePlugins($container);
        $container = $this->addProductConcreteBeforeCreatePlugins($container);
        $container = $this->addProductConcreteAfterCreatePlugins($container);
        $container = $this->addProductConcreteReadPlugins($container);
        $container = $this->addProductConcreteBeforeUpdatePlugins($container);
        $container = $this->addProductConcreteAfterUpdatePlugins($container);
        $container = $this->addProductAbstractPreCreatePlugins($container);
        $container = $this->addProductConcreteExpanderPlugins($container);
        $container = $this->addMessageBrokerFacade($container);
        $container = $this->addProductConcreteMergerPlugins($container);
        $container = $this->addProductAbstractCollectionExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductToLocaleBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ProductToStoreBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUrlFacade(Container $container): Container
    {
        $container->set(static::FACADE_URL, function (Container $container) {
            return new ProductToUrlBridge($container->getLocator()->url()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchFacade(Container $container): Container
    {
        $container->set(static::FACADE_TOUCH, function (Container $container) {
            return new ProductToTouchBridge($container->getLocator()->touch()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new ProductToUtilTextBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT, function (Container $container) {
            return new ProductToEventBridge($container->getLocator()->event()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractBeforeCreatePlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_ABSTRACT_PLUGINS_BEFORE_CREATE, function (Container $container) {
            return $this->getProductAbstractBeforeCreatePlugins($container);
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\ProductDependencyProvider::addProductAbstractPostCreatePlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractAfterCreatePlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE, function (Container $container) {
            return $this->getProductAbstractAfterCreatePlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_POST_CREATE, function () {
            return $this->getProductAbstractPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONCRETE_EXPANDER, function () {
            return $this->getProductConcreteExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface>
     */
    protected function getProductConcreteExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\ProductDependencyProvider::addProductAbstractExpanderPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractReadPlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_ABSTRACT_PLUGINS_READ, function (Container $container) {
            return $this->getProductAbstractReadPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_EXPANDER, function (Container $container) {
            return $this->getProductAbstractExpanderPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractBeforeUpdatePlugin(Container $container): Container
    {
        $container->set(static::PRODUCT_ABSTRACT_PLUGINS_BEFORE_UPDATE, function (Container $container) {
            return $this->getProductAbstractBeforeUpdatePlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractAfterUpdatePlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_ABSTRACT_PLUGINS_AFTER_UPDATE, function (Container $container) {
            return $this->getProductAbstractAfterUpdatePlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteBeforeCreatePlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_CONCRETE_PLUGINS_BEFORE_CREATE, function (Container $container) {
            return $this->getProductConcreteBeforeCreatePlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteAfterCreatePlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_CONCRETE_PLUGINS_AFTER_CREATE, function (Container $container) {
            return $this->getProductConcreteAfterCreatePlugins($container);
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteReadPlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_CONCRETE_PLUGINS_READ, function (Container $container) {
            return $this->getProductConcreteReadPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteBeforeUpdatePlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_CONCRETE_PLUGINS_BEFORE_UPDATE, function (Container $container) {
            return $this->getProductConcreteBeforeUpdatePlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteAfterUpdatePlugins(Container $container): Container
    {
        $container->set(static::PRODUCT_CONCRETE_PLUGINS_AFTER_UPDATE, function (Container $container) {
            return $this->getProductConcreteAfterUpdatePlugins($container);
        });

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
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addUrlQueryContainer($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUrlQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_URL, function (Container $container) {
            return new ProductToUrlQueryContainerBridge($container->getLocator()->url()->queryContainer());
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface>
     */
    protected function getProductAbstractBeforeCreatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\ProductDependencyProvider::getProductAbstractPostCreatePlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface>
     */
    protected function getProductAbstractAfterCreatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPostCreatePluginInterface>
     */
    protected function getProductAbstractPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\ProductDependencyProvider::getProductAbstractExpanderPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface>
     */
    protected function getProductAbstractReadPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractExpanderPluginInterface>
     */
    protected function getProductAbstractExpanderPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface>
     */
    protected function getProductAbstractBeforeUpdatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface>
     */
    protected function getProductAbstractAfterUpdatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteCreatePluginInterface>
     */
    protected function getProductConcreteBeforeCreatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteCreatePluginInterface>
     */
    protected function getProductConcreteAfterCreatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface>
     */
    protected function getProductConcreteReadPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface>
     */
    protected function getProductConcreteBeforeUpdatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface>
     */
    protected function getProductConcreteAfterUpdatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractPreCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_PRE_CREATE, function () {
            return $this->getProductAbstractPreCreatePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPreCreatePluginInterface>
     */
    protected function getProductAbstractPreCreatePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessageBrokerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSAGE_BROKER, function (Container $container) {
            return new ProductToMessageBrokerBridge($container->getLocator()->messageBroker()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteMergerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONCRETE_MERGER, function () {
            return $this->getProductConcreteMergerPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteMergerPluginInterface>
     */
    protected function getProductConcreteMergerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractCollectionExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_COLLECTION_EXPANDER, function () {
            return $this->getProductAbstractCollectionExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractCollectionExpanderPluginInterface>
     */
    protected function getProductAbstractCollectionExpanderPlugins(): array
    {
        return [];
    }
}
