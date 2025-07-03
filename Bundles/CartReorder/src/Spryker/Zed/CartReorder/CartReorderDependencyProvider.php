<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorder;

use Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToCartFacadeBridge;
use Spryker\Zed\CartReorder\Dependency\Facade\CartReorderToSalesFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CartReorder\CartReorderConfig getConfig()
 */
class CartReorderDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CART = 'FACADE_CART';

    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const PLUGINS_CART_REORDER_REQUEST_VALIDATOR = 'PLUGINS_CART_REORDER_REQUEST_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_CART_REORDER_QUOTE_PROVIDER_STRATEGY = 'PLUGINS_CART_REORDER_QUOTE_PROVIDER_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_CART_REORDER_ORDER_ITEM_FILTER = 'PLUGINS_CART_REORDER_ORDER_ITEM_FILTER';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGINS_CART_REORDER_ITEM_FILTER = 'PLUGINS_CART_REORDER_ITEM_FILTER';

    /**
     * @var string
     */
    public const PLUGINS_CART_REORDER_VALIDATOR = 'PLUGINS_CART_REORDER_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_CART_REORDER_VALIDATOR_FOR_ORDER_AMENDMENT = 'PLUGINS_CART_REORDER_VALIDATOR_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const PLUGINS_CART_PRE_REORDER = 'PLUGINS_CART_PRE_REORDER';

    /**
     * @var string
     */
    public const PLUGINS_CART_REORDER_ITEM_HYDRATOR = 'PLUGINS_CART_REORDER_ITEM_HYDRATOR';

    /**
     * @var string
     */
    public const PLUGINS_CART_REORDER_PRE_ADD_TO_CART = 'PLUGINS_CART_REORDER_PRE_ADD_TO_CART';

    /**
     * @var string
     */
    public const PLUGINS_CART_REORDER_PRE_ADD_TO_CART_FOR_ORDER_AMENDMENT = 'PLUGINS_CART_REORDER_PRE_ADD_TO_CART_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const PLUGINS_CART_POST_REORDER = 'PLUGINS_CART_POST_REORDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCartFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addCartReorderRequestValidatorPlugins($container);
        $container = $this->addCartReorderQuoteProviderStrategyPlugins($container);
        $container = $this->addCartReorderOrderItemFilterPlugins($container);
        $container = $this->addCartReorderValidatorPlugins($container);
        $container = $this->addCartReorderValidatorPluginsForOrderAmendment($container);
        $container = $this->addCartPreReorderPlugins($container);
        $container = $this->addCartReorderItemHydratorPlugins($container);
        $container = $this->addCartReorderPreAddToCartPlugins($container);
        $container = $this->addCartReorderPreAddToCartPluginsForOrderAmendment($container);
        $container = $this->addCartPostReorderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartFacade(Container $container): Container
    {
        $container->set(static::FACADE_CART, function (Container $container) {
            return new CartReorderToCartFacadeBridge($container->getLocator()->cart()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new CartReorderToSalesFacadeBridge($container->getLocator()->sales()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartReorderRequestValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_REORDER_REQUEST_VALIDATOR, function () {
            return $this->getCartReorderRequestValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartReorderQuoteProviderStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_REORDER_QUOTE_PROVIDER_STRATEGY, function () {
            return $this->getCartReorderQuoteProviderStrategyPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartReorderOrderItemFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_REORDER_ORDER_ITEM_FILTER, function () {
            return $this->getCartReorderOrderItemFilterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartReorderValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_REORDER_VALIDATOR, function () {
            return $this->getCartReorderValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartReorderValidatorPluginsForOrderAmendment(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_REORDER_VALIDATOR_FOR_ORDER_AMENDMENT, function () {
            return $this->getCartReorderValidatorPluginsForOrderAmendment();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartPreReorderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_PRE_REORDER, function () {
            return $this->getCartPreReorderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartReorderItemHydratorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_REORDER_ITEM_HYDRATOR, function () {
            return $this->getCartReorderItemHydratorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartReorderPreAddToCartPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_REORDER_PRE_ADD_TO_CART, function () {
            return $this->getCartReorderPreAddToCartPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartReorderPreAddToCartPluginsForOrderAmendment(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_REORDER_PRE_ADD_TO_CART_FOR_ORDER_AMENDMENT, function () {
            return $this->getCartReorderPreAddToCartPluginsForOrderAmendment();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartPostReorderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_POST_REORDER, function () {
            return $this->getCartPostReorderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderRequestValidatorPluginInterface>
     */
    protected function getCartReorderRequestValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface>
     */
    protected function getCartReorderQuoteProviderStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderOrderItemFilterPluginInterface>
     */
    protected function getCartReorderOrderItemFilterPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderValidatorPluginInterface>
     */
    protected function getCartReorderValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderValidatorPluginInterface>
     */
    protected function getCartReorderValidatorPluginsForOrderAmendment(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface>
     */
    protected function getCartPreReorderPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemHydratorPluginInterface>|array<string, list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemHydratorPluginInterface>>
     */
    protected function getCartReorderItemHydratorPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderPreAddToCartPluginInterface>
     */
    protected function getCartReorderPreAddToCartPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderPreAddToCartPluginInterface>
     */
    protected function getCartReorderPreAddToCartPluginsForOrderAmendment(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPostReorderPluginInterface>|array<string, list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPostReorderPluginInterface>>
     */
    protected function getCartPostReorderPlugins(): array
    {
        return [];
    }
}
