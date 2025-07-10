<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart;

use Spryker\Zed\Cart\Dependency\Facade\CartToCalculationBridge;
use Spryker\Zed\Cart\Dependency\Facade\CartToMessengerBridge;
use Spryker\Zed\Cart\Dependency\Facade\CartToQuoteFacadeBridge;
use Spryker\Zed\Cart\Dependency\Service\CartToUtilTextServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Cart\CartConfig getConfig()
 */
class CartDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CALCULATION = 'calculation facade';

    /**
     * @var string
     */
    public const FACADE_MESSENGER = 'messenger facade';

    /**
     * @var string
     */
    public const FACADE_QUOTE = 'FACADE_QUOTE';

    /**
     * @var string
     */
    public const CART_EXPANDER_PLUGINS = 'cart expander plugins';

    /**
     * @var string
     */
    public const CART_EXPANDER_PLUGINS_FOR_ORDER_AMENDMENT = 'CART_EXPANDER_PLUGINS_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const CART_EXPANDER_PLUGINS_FOR_ORDER_AMENDMENT_ASYNC = 'CART_EXPANDER_PLUGINS_FOR_ORDER_AMENDMENT_ASYNC';

    /**
     * @var string
     */
    public const CART_PRE_CHECK_PLUGINS = 'pre check plugins';

    /**
     * @var string
     */
    public const CART_PRE_CHECK_PLUGINS_FOR_ORDER_AMENDMENT = 'CART_PRE_CHECK_PLUGINS_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const CART_PRE_CHECK_PLUGINS_FOR_ORDER_AMENDMENT_ASYNC = 'CART_PRE_CHECK_PLUGINS_FOR_ORDER_AMENDMENT_ASYNC';

    /**
     * @var string
     */
    public const CART_BEFORE_PRE_CHECK_NORMALIZER_PLUGINS = 'CART_BEFORE_PRE_CHECK_NORMALIZER_PLUGINS';

    /**
     * @var string
     */
    public const CART_REMOVAL_PRE_CHECK_PLUGINS = 'CART_REMOVAL_PRE_CHECK_PLUGINS';

    /**
     * @var string
     */
    public const CART_POST_SAVE_PLUGINS = 'cart post save plugins';

    /**
     * @var string
     */
    public const CART_PRE_RELOAD_PLUGINS = 'cart pre reload plugins';

    /**
     * @var string
     */
    public const CART_PRE_RELOAD_PLUGINS_FOR_ORDER_AMENDMENT = 'CART_PRE_RELOAD_PLUGINS_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const CART_PRE_RELOAD_PLUGINS_FOR_ORDER_AMENDMENT_ASYNC = 'CART_PRE_RELOAD_PLUGINS_FOR_ORDER_AMENDMENT_ASYNC';

    /**
     * @var string
     */
    public const CART_TERMINATION_PLUGINS = 'CART_TERMINATION_PLUGINS';

    /**
     * @var string
     */
    public const PLUGINS_QUOTE_CHANGE_OBSERVER = 'PLUGINS_QUOTE_CHANGE_OBSERVER';

    /**
     * @var string
     */
    public const PLUGINS_CART_ADD_ITEM_STRATEGY = 'PLUGINS_CART_ADD_ITEM_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_CART_REMOVE_ITEM_STRATEGY = 'PLUGINS_CART_REMOVE_ITEM_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_POST_RELOAD_ITEMS = 'PLUGINS_POST_RELOAD_ITEMS';

    /**
     * @var string
     */
    public const PLUGINS_QUOTE_LOCK_PRE_RESET = 'PLUGINS_QUOTE_LOCK_PRE_RESET';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

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
        $container = $this->addExpanderPluginsForOrderAmendment($container);
        $container = $this->addExpanderPluginsForOrderAmendmentAsync($container);
        $container = $this->addPostSavePlugins($container);
        $container = $this->addPreCheckPlugins($container);
        $container = $this->addPreCheckPluginsForOrderAmendment($container);
        $container = $this->addPreCheckPluginsForOrderAmendmentAsync($container);
        $container = $this->addCartRemovalPreCheckPlugins($container);
        $container = $this->addPreReloadPlugins($container);
        $container = $this->addPreReloadPluginsForOrderAmendment($container);
        $container = $this->addPreReloadPluginsForOrderAmendmentAsync($container);
        $container = $this->addTerminationPlugins($container);
        $container = $this->addQuoteChangeObserverPlugins($container);
        $container = $this->addCartAddItemStrategyPlugins($container);
        $container = $this->addCartRemoveItemStrategyPlugins($container);
        $container = $this->addPostReloadItemsPlugins($container);
        $container = $this->addCartBeforePreCheckNormalizerPlugins($container);
        $container = $this->addQuoteLockPreResetPlugins($container);
        $container = $this->addUtilTextService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container)
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new CartToCalculationBridge($container->getLocator()->calculation()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container)
    {
        $container->set(static::FACADE_QUOTE, function (Container $container) {
            return new CartToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container)
    {
        $container->set(static::FACADE_MESSENGER, function (Container $container) {
            return new CartToMessengerBridge($container->getLocator()->messenger()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addExpanderPlugins(Container $container)
    {
        $container->set(static::CART_EXPANDER_PLUGINS, function (Container $container) {
            return $this->getExpanderPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addExpanderPluginsForOrderAmendment(Container $container)
    {
        $container->set(static::CART_EXPANDER_PLUGINS_FOR_ORDER_AMENDMENT, function (Container $container) {
            return $this->getExpanderPluginsForOrderAmendment($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addExpanderPluginsForOrderAmendmentAsync(Container $container): Container
    {
        $container->set(static::CART_EXPANDER_PLUGINS_FOR_ORDER_AMENDMENT_ASYNC, function (Container $container): array {
            return $this->getExpanderPluginsForOrderAmendmentAsync($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostSavePlugins(Container $container)
    {
        $container->set(static::CART_POST_SAVE_PLUGINS, function (Container $container) {
            return $this->getPostSavePlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartBeforePreCheckNormalizerPlugins(Container $container): Container
    {
        $container->set(static::CART_BEFORE_PRE_CHECK_NORMALIZER_PLUGINS, function (Container $container): array {
            return $this->getCartBeforePreCheckNormalizerPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPreCheckPlugins(Container $container)
    {
        $container->set(static::CART_PRE_CHECK_PLUGINS, function (Container $container) {
            return $this->getCartPreCheckPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPreCheckPluginsForOrderAmendment(Container $container): Container
    {
        $container->set(static::CART_PRE_CHECK_PLUGINS_FOR_ORDER_AMENDMENT, function () {
            return $this->getCartPreCheckPluginsForOrderAmendment();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPreCheckPluginsForOrderAmendmentAsync(Container $container): Container
    {
        $container->set(static::CART_PRE_CHECK_PLUGINS_FOR_ORDER_AMENDMENT_ASYNC, function (): array {
            return $this->getCartPreCheckPluginsForOrderAmendmentAsync();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartRemovalPreCheckPlugins(Container $container)
    {
        $container->set(static::CART_REMOVAL_PRE_CHECK_PLUGINS, function (Container $container) {
            return $this->getCartRemovalPreCheckPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPreReloadPlugins(Container $container)
    {
        $container->set(static::CART_PRE_RELOAD_PLUGINS, function (Container $container) {
            return $this->getPreReloadPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPreReloadPluginsForOrderAmendment(Container $container)
    {
        $container->set(static::CART_PRE_RELOAD_PLUGINS_FOR_ORDER_AMENDMENT, function (Container $container) {
            return $this->getPreReloadPluginsForOrderAmendment($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPreReloadPluginsForOrderAmendmentAsync(Container $container): Container
    {
        $container->set(static::CART_PRE_RELOAD_PLUGINS_FOR_ORDER_AMENDMENT_ASYNC, function (Container $container): array {
            return $this->getPreReloadPluginsForOrderAmendmentAsync($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTerminationPlugins(Container $container)
    {
        $container->set(static::CART_TERMINATION_PLUGINS, function (Container $container) {
            return $this->getTerminationPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteChangeObserverPlugins(Container $container)
    {
        $container->set(static::PLUGINS_QUOTE_CHANGE_OBSERVER, function (Container $container) {
            return $this->getQuoteChangeObserverPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartAddItemStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_ADD_ITEM_STRATEGY, function (Container $container) {
            return $this->getCartAddItemStrategyPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartRemoveItemStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_REMOVE_ITEM_STRATEGY, function (Container $container) {
            return $this->getCartRemoveItemStrategyPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostReloadItemsPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_RELOAD_ITEMS, function (Container $container): array {
            return $this->getPostReloadItemsPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteLockPreResetPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUOTE_LOCK_PRE_RESET, function () {
            return $this->getQuoteLockPreResetPlugins();
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
            return new CartToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface>
     */
    protected function getExpanderPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface>
     */
    protected function getExpanderPluginsForOrderAmendment(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface>
     */
    protected function getExpanderPluginsForOrderAmendmentAsync(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationPostSavePluginInterface>
     */
    protected function getPostSavePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface>
     */
    protected function getCartPreCheckPlugins(Container $container)
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface>
     */
    protected function getCartPreCheckPluginsForOrderAmendment(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface>
     */
    protected function getCartPreCheckPluginsForOrderAmendmentAsync(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\CartChangeTransferNormalizerPluginInterface>
     */
    protected function getCartBeforePreCheckNormalizerPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\CartRemovalPreCheckPluginInterface>
     */
    protected function getCartRemovalPreCheckPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface>
     */
    protected function getPreReloadPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface>
     */
    protected function getPreReloadPluginsForOrderAmendment(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return list<\Spryker\Zed\CartExtension\Dependency\Plugin\PreReloadItemsPluginInterface>
     */
    protected function getPreReloadPluginsForOrderAmendmentAsync(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\CartTerminationPluginInterface>
     */
    protected function getTerminationPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\QuoteChangeObserverPluginInterface>
     */
    protected function getQuoteChangeObserverPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyPluginInterface>
     */
    protected function getCartAddItemStrategyPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationStrategyPluginInterface>
     */
    protected function getCartRemoveItemStrategyPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\PostReloadItemsPluginInterface>
     */
    protected function getPostReloadItemsPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\CartExtension\Dependency\Plugin\QuoteLockPreResetPluginInterface>
     */
    protected function getQuoteLockPreResetPlugins(): array
    {
        return [];
    }
}
