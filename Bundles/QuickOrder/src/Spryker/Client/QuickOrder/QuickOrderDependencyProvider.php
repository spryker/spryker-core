<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToLocaleClientBridge;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToLocaleClientInterface;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientBridge;

class QuickOrderDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const PLUGINS_PRODUCT_CONCRETE_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_EXPANDER';
    public const PLUGINS_QUICK_ORDER_BUILD_ITEM_VALIDATOR = 'PLUGINS_QUICK_ORDER_BUILD_ITEM_VALIDATOR';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addProductStorageClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addProductConcreteExpanderPlugins($container);
        $container = $this->addQuickOrderValidationPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container[static::CLIENT_LOCALE] = function (Container $container): QuickOrderToLocaleClientInterface {
            return new QuickOrderToLocaleClientBridge($container->getLocator()->locale()->client());
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
        $container[static::PLUGINS_PRODUCT_CONCRETE_EXPANDER] = function (): array {
            return $this->getProductConcreteExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuickOrderValidationPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUICK_ORDER_BUILD_ITEM_VALIDATOR] = function (): array {
            return $this->getQuickOrderBuildItemValidatorPlugins();
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

    /**
     * @return \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ItemValidatorPluginInterface[]
     */
    protected function getQuickOrderBuildItemValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new QuickOrderToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }
}
