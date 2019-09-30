<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart;

use Spryker\Zed\Cart\Dependency\Facade\CartToCalculationBridge;
use Spryker\Zed\Cart\Dependency\Facade\CartToMessengerBridge;
use Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Cart\CartConfig getConfig()
 */
class CartDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CALCULATION = 'calculation facade';
    public const FACADE_MESSENGER = 'messenger facade';
    public const FACADE_QUOTE = 'FACADE_QUOTE';

    public const CART_EXPANDER_PLUGINS = 'cart expander plugins';
    public const CART_PRE_CHECK_PLUGINS = 'pre check plugins';
    public const CART_BEFORE_PRE_CHECK_NORMALIZER_PLUGINS = 'CART_BEFORE_PRE_CHECK_NORMALIZER_PLUGINS';
    public const CART_REMOVAL_PRE_CHECK_PLUGINS = 'CART_REMOVAL_PRE_CHECK_PLUGINS';
    public const CART_POST_SAVE_PLUGINS = 'cart post save plugins';
    public const CART_PRE_RELOAD_PLUGINS = 'cart pre reload plugins';
    public const CART_TERMINATION_PLUGINS = 'CART_TERMINATION_PLUGINS';
    public const PLUGINS_QUOTE_CHANGE_OBSERVER = 'PLUGINS_QUOTE_CHANGE_OBSERVER';
    public const PLUGINS_CART_ADD_ITEM_STRATEGY = 'PLUGINS_CART_ADD_ITEM_STRATEGY';
    public const PLUGINS_CART_REMOVE_ITEM_STRATEGY = 'PLUGINS_CART_REMOVE_ITEM_STRATEGY';
    public const PLUGINS_POST_RELOAD_ITEMS = 'PLUGINS_POST_RELOAD_ITEMS';
    public const PLUGINS_QUOTE_LOCK_PRE_RESET = 'PLUGINS_QUOTE_LOCK_PRE_RESET';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addCalculationFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addQuoteFacade($container);
        $container = $this->addExpanderPlugins($container);
        $container = $this->addPostSavePlugins($container);
        $container = $this->addPreCheckPlugins($container);
        $container = $this->addCartRemovalPreCheckPlugins($container);
        $container = $this->addPreReloadPlugins($container);
        $container = $this->addTerminationPlugins($container);
        $container = $this->addQuoteChangeObserverPlugins($container);
        $container = $this->addCartAddItemStrategyPlugins($container);
        $container = $this->addCartRemoveItemStrategyPlugins($container);
        $container = $this->addPostReloadItemsPlugins($container);
        $container = $this->addCartBeforePreCheckNormalizerPlugins($container);
        $container = $this->addQuoteLockPreResetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container)
    {
        $container[static::FACADE_CALCULATION] = function (Container $container) {
            return new CartToCalculationBridge($container->getLocator()->calculation()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container)
    {
        $container[static::FACADE_QUOTE] = function (Container $container) {
            return new CartToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container)
    {
        $container[static::FACADE_MESSENGER] = function (Container $container) {
            return new CartToMessengerBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addExpanderPlugins(Container $container)
    {
        $container[static::CART_EXPANDER_PLUGINS] = function (Container $container) {
            return $this->getExpanderPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostSavePlugins(Container $container)
    {
        $container[static::CART_POST_SAVE_PLUGINS] = function (Container $container) {
            return $this->getPostSavePlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartBeforePreCheckNormalizerPlugins(Container $container): Container
    {
        $container[static::CART_BEFORE_PRE_CHECK_NORMALIZER_PLUGINS] = function (Container $container): array {
            return $this->getCartBeforePreCheckNormalizerPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPreCheckPlugins(Container $container)
    {
        $container[static::CART_PRE_CHECK_PLUGINS] = function (Container $container) {
            return $this->getCartPreCheckPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartRemovalPreCheckPlugins(Container $container)
    {
        $container[static::CART_REMOVAL_PRE_CHECK_PLUGINS] = function (Container $container) {
            return $this->getCartRemovalPreCheckPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPreReloadPlugins(Container $container)
    {
        $container[static::CART_PRE_RELOAD_PLUGINS] = function (Container $container) {
            return $this->getPreReloadPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTerminationPlugins(Container $container)
    {
        $container[static::CART_TERMINATION_PLUGINS] = function (Container $container) {
            return $this->getTerminationPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteChangeObserverPlugins(Container $container)
    {
        $container[static::PLUGINS_QUOTE_CHANGE_OBSERVER] = function (Container $container) {
            return $this->getQuoteChangeObserverPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartAddItemStrategyPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CART_ADD_ITEM_STRATEGY] = function (Container $container) {
            return $this->getCartAddItemStrategyPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartRemoveItemStrategyPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CART_REMOVE_ITEM_STRATEGY] = function (Container $container) {
            return $this->getCartRemoveItemStrategyPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostReloadItemsPlugins(Container $container): Container
    {
        $container[static::PLUGINS_POST_RELOAD_ITEMS] = function (Container $container): array {
            return $this->getPostReloadItemsPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteLockPreResetPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUOTE_LOCK_PRE_RESET] = function () {
            return $this->getQuoteLockPreResetPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[]
     */
    protected function getExpanderPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\PostSavePluginInterface[]
     */
    protected function getPostSavePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface[]
     */
    protected function getCartPreCheckPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\CartChangeTransferNormalizerPluginInterface[]
     */
    protected function getCartBeforePreCheckNormalizerPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\CartRemovalPreCheckPluginInterface[]
     */
    protected function getCartRemovalPreCheckPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface[]
     */
    protected function getPreReloadPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\CartTerminationPluginInterface[]
     */
    protected function getTerminationPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\QuoteChangeObserverPluginInterface[]
     */
    protected function getQuoteChangeObserverPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyPluginInterface[]
     */
    protected function getCartAddItemStrategyPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyPluginInterface[]
     */
    protected function getCartRemoveItemStrategyPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\PostReloadItemsPluginInterface[]
     */
    protected function getPostReloadItemsPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\CartExtension\Dependency\Plugin\QuoteLockPreResetPluginInterface[]
     */
    protected function getQuoteLockPreResetPlugins(): array
    {
        return [];
    }
}
