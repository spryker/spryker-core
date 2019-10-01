<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToLocaleBridge;
use Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientBridge;
use Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceBridge;
use Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToUtilEncodingServiceInterface;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Spryker\Client\ProductStorage\ProductStorageConfig getConfig()
 */
class ProductStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const STORE = 'STORE';
    public const PLUGIN_PRODUCT_VIEW_EXPANDERS = 'PLUGIN_STORAGE_PRODUCT_EXPANDERS';
    public const PLUGINS_PRODUCT_ABSTRACT_RESTRICTION = 'PLUGINS_PRODUCT_ABSTRACT_RESTRICTION';
    public const PLUGINS_PRODUCT_CONCRETE_RESTRICTION = 'PLUGINS_PRODUCT_CONCRETE_RESTRICTION';
    public const PLUGINS_PRODUCT_CONCRETE_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_EXPANDER';
    public const PLUGINS_PRODUCT_ABSTRACT_RESTRICTION_FILTER = 'PLUGINS_PRODUCT_ABSTRACT_RESTRICTION_FILTER';
    public const PLUGINS_PRODUCT_CONCRETE_RESTRICTION_FILTER = 'PLUGINS_PRODUCT_CONCRETE_RESTRICTION_FILTER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addStore($container);
        $container = $this->addProductViewExpanderPlugins($container);
        $container = $this->addProductAbstractRestrictionPlugins($container);
        $container = $this->addProductConcreteRestrictionPlugins($container);
        $container = $this->addProductConcreteExpanderPlugins($container);
        $container = $this->addProductAbstractRestrictionFilterPlugins($container);
        $container = $this->addProductConcreteRestrictionFilterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container)
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new ProductStorageToStorageClientBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container)
    {
        $container[static::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new ProductStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductStorageToUtilEncodingServiceInterface(
                $container
                    ->getLocator()
                    ->utilEncoding()
                    ->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container)
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new ProductStorageToLocaleBridge($container->getLocator()->locale()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductViewExpanderPlugins(Container $container)
    {
        $container[static::PLUGIN_PRODUCT_VIEW_EXPANDERS] = function () {
            return $this->getProductViewExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductAbstractRestrictionPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_ABSTRACT_RESTRICTION] = function () {
            return $this->getProductAbstractRestrictionPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConcreteRestrictionPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_CONCRETE_RESTRICTION] = function () {
            return $this->getProductConcreteRestrictionPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConcreteExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_CONCRETE_EXPANDER] = function () {
            return $this->getProductConcreteExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductAbstractRestrictionFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_RESTRICTION_FILTER, function () {
            return $this->getProductAbstractRestrictionFilterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductConcreteRestrictionFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONCRETE_RESTRICTION_FILTER, function () {
            return $this->getProductConcreteRestrictionFilterPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected function getProductViewExpanderPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractRestrictionPluginInterface[]
     */
    protected function getProductAbstractRestrictionPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductConcreteRestrictionPluginInterface[]
     */
    protected function getProductConcreteRestrictionPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[]
     */
    protected function getProductConcreteExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductAbstractRestrictionFilterPluginInterface[]
     */
    protected function getProductAbstractRestrictionFilterPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductConcreteRestrictionFilterPluginInterface[]
     */
    protected function getProductConcreteRestrictionFilterPlugins(): array
    {
        return [];
    }
}
