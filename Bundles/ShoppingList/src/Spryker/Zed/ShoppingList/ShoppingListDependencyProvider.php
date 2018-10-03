<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeBridge;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToEventFacadeBridge;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeBridge;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPermissionFacadeBridge;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPersistentCartFacadeBridge;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeBridge;

class ShoppingListDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_PERMISSION = 'FACADE_PERMISSION';
    public const FACADE_PERSISTENT_CART = 'FACADE_PERSISTENT_CART';
    public const FACADE_EVENT = 'FACADE_EVENT';

    public const PLUGINS_ITEM_EXPANDER = 'PLUGINS_ITEM_EXPANDER';
    public const PLUGINS_QUOTE_ITEM_EXPANDER = 'PLUGINS_QUOTE_ITEM_EXPANDER';
    public const PLUGINS_ADD_ITEM_PRE_CHECK = 'PLUGINS_ADD_ITEM_PRE_CHECK';
    public const PLUGINS_SHOPPING_LIST_ITEM_POST_SAVE = 'PLUGINS_SHOPPING_LIST_ITEM_POST_SAVE';
    public const PLUGINS_SHOPPING_LIST_ITEM_BEFORE_DELETE = 'PLUGINS_SHOPPING_LIST_ITEM_BEFORE_DELETE';
    public const PLUGINS_ITEM_TO_SHOPPING_LIST_ITEM_MAPPER = 'PLUGINS_ITEM_TO_SHOPPING_LIST_ITEM_MAPPER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addMessengerFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addPermissionFacade($container);
        $container = $this->addPersistentCartFacade($container);
        $container = $this->addEventFacade($container);
        $container = $this->addCompanyUserFacade($container);

        $container = $this->addItemExpanderPlugins($container);
        $container = $this->addQuoteItemExpanderPlugins($container);
        $container = $this->addAddItemPreCheckPlugins($container);
        $container = $this->addShoppingListItemPostSavePlugins($container);
        $container = $this->addBeforeDeleteShoppingListItemPlugins($container);
        $container = $this->addItemToShoppingListItemMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ShoppingListToProductFacadeBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPermissionFacade(Container $container): Container
    {
        $container[static::FACADE_PERMISSION] = function (Container $container) {
            return new ShoppingListToPermissionFacadeBridge($container->getLocator()->permission()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPersistentCartFacade(Container $container): Container
    {
        $container[static::FACADE_PERSISTENT_CART] = function (Container $container) {
            return new ShoppingListToPersistentCartFacadeBridge($container->getLocator()->persistentCart()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_USER] = function (Container $container) {
            return new ShoppingListToCompanyUserFacadeBridge($container->getLocator()->companyUser()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container[static::FACADE_MESSENGER] = function (Container $container) {
            return new ShoppingListToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventFacade(Container $container): Container
    {
        $container[static::FACADE_EVENT] = function (Container $container) {
            return new ShoppingListToEventFacadeBridge($container->getLocator()->event()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addItemExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_ITEM_EXPANDER] = function () {
            return $this->getItemExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteItemExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUOTE_ITEM_EXPANDER] = function () {
            return $this->getQuoteItemExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAddItemPreCheckPlugins(Container $container): Container
    {
        $container[static::PLUGINS_ADD_ITEM_PRE_CHECK] = function () {
            return $this->getAddItemPreCheckPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemExpanderPluginInterface[]
     */
    protected function getItemExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\AddItemPreCheckPluginInterface[]
     */
    protected function getAddItemPreCheckPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\QuoteItemsPreConvertPluginInterface[]
     */
    protected function getQuoteItemExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShoppingListItemPostSavePlugins(Container $container): Container
    {
        $container[static::PLUGINS_SHOPPING_LIST_ITEM_POST_SAVE] = function () {
            return $this->getShoppingListItemPostSavePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemPostSavePluginInterface[]
     */
    protected function getShoppingListItemPostSavePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addBeforeDeleteShoppingListItemPlugins(Container $container): Container
    {
        $container[static::PLUGINS_SHOPPING_LIST_ITEM_BEFORE_DELETE] = function () {
            return $this->getBeforeDeleteShoppingListItemPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBeforeDeletePluginInterface[]
     */
    protected function getBeforeDeleteShoppingListItemPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addItemToShoppingListItemMapperPlugins(Container $container): Container
    {
        $container[static::PLUGINS_ITEM_TO_SHOPPING_LIST_ITEM_MAPPER] = function () {
            return $this->getItemToShoppingListItemMapperPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemToShoppingListItemMapperPluginInterface[]
     */
    protected function getItemToShoppingListItemMapperPlugins(): array
    {
        return [];
    }
}
