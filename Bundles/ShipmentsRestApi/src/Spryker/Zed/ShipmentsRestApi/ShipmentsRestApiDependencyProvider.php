<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentsRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShipmentsRestApi\Dependency\Facade\ShipmentsRestApiToShipmentFacadeAdapter;

/**
 * @method \Spryker\Zed\ShipmentsRestApi\ShipmentsRestApiConfig getConfig()
 */
class ShipmentsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_SHIPMENT = 'FACADE_SHIPMENT';

    /**
     * @var string
     */
    public const PLUGINS_ADDRESS_PROVIDER_STRATEGY = 'PLUGINS_ADDRESS_PROVIDER_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_QUOTE_ITEM_EXPANDER = 'PLUGINS_QUOTE_ITEM_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addShipmentFacade($container);
        $container = $this->addAddressProviderStrategyPlugins($container);
        $container = $this->addQuoteItemExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT, function (Container $container) {
            return new ShipmentsRestApiToShipmentFacadeAdapter($container->getLocator()->shipment()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAddressProviderStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ADDRESS_PROVIDER_STRATEGY, function () {
            return $this->getAddressProviderStrategyPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteItemExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUOTE_ITEM_EXPANDER, function () {
            return $this->getQuoteItemExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin\AddressProviderStrategyPluginInterface>
     */
    protected function getAddressProviderStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin\QuoteItemExpanderPluginInterface>
     */
    protected function getQuoteItemExpanderPlugins(): array
    {
        return [];
    }
}
