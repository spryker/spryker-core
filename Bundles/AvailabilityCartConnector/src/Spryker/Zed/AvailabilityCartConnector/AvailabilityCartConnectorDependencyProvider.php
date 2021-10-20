<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector;

use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AvailabilityCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_AVAILABILITY = 'FACADE_AVAILABILITY';

    /**
     * @var string
     */
    public const PLUGINS_CART_ITEM_QUANTITY_COUNTER_STRATEGY = 'PLUGINS_CART_ITEM_QUANTITY_COUNTER_STRATEGY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addAvailabilityFacade($container);
        $container = $this->addCartItemQuantityCounterStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAvailabilityFacade(Container $container): Container
    {
        $container->set(static::FACADE_AVAILABILITY, function (Container $container) {
            return new AvailabilityCartConnectorToAvailabilityBridge($container->getLocator()->availability()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartItemQuantityCounterStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_ITEM_QUANTITY_COUNTER_STRATEGY, function () {
            return $this->getCartItemQuantityCounterStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface>
     */
    public function getCartItemQuantityCounterStrategyPlugins(): array
    {
        return [];
    }
}
