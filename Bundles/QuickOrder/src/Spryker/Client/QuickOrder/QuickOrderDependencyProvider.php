<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToLocaleClientBridge;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductClientBridge;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductStorageClientBridge;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityStorageClientBridge;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientBridge;

class QuickOrderDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_PRICE_PRODUCT = 'CLIENT_PRICE_PRODUCT';
    public const CLIENT_PRICE_PRODUCT_STORAGE = 'CLIENT_PRICE_PRODUCT_STORAGE';
    public const CLIENT_PRODUCT_QUANTITY_STORAGE = 'CLIENT_PRODUCT_QUANTITY_STORAGE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';
    public const PLUGINS_PRODUCT_CONCRETE_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_EXPANDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addProductConcreteExpanderPlugins($container);
        $container = $this->addPriceProductClient($container);
        $container = $this->addPriceProductStorageClient($container);
        $container = $this->addProductQuantityStorageClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addLocaleClient($container);

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
    protected function addPriceProductClient(Container $container): Container
    {
        $container[static::CLIENT_PRICE_PRODUCT] = function (Container $container) {
            return new QuickOrderToPriceProductClientBridge(
                $container->getLocator()->priceProduct()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRICE_PRODUCT_STORAGE] = function (Container $container) {
            return new QuickOrderToPriceProductStorageClientBridge(
                $container->getLocator()->priceProductStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductQuantityStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_QUANTITY_STORAGE] = function (Container $container) {
            return new QuickOrderToProductQuantityStorageClientBridge(
                $container->getLocator()->productQuantityStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new QuickOrderToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new QuickOrderToLocaleClientBridge(
                $container->getLocator()->locale()->client()
            );
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[]
     */
    protected function getProductConcreteExpanderPlugins(): array
    {
        return [];
    }
}
