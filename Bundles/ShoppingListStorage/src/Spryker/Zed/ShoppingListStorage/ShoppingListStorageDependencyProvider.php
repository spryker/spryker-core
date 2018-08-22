<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShoppingListStorage\Dependency\Facade\ShoppingListStorageToEventBehaviorFacadeBridge;

class ShoppingListStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const PROPEL_QUERY_SHOPPING_LIST = 'PROPEL_QUERY_SHOPPING_LIST';
    public const PROPEL_QUERY_COMPANY_USER = 'PROPEL_QUERY_COMPANY_USER';
    public const PROPEL_QUERY_SHOPPING_LIST_ITEM = 'PROPEL_QUERY_SHOPPING_LIST_ITEM';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addShoppingListPropelQuery($container);
        $container = $this->addShoppingListItemPropelQuery($container);
        $container = $this->addCompanyUserPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ShoppingListStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShoppingListPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_SHOPPING_LIST] = function (Container $container) {
            return SpyShoppingListQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShoppingListItemPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_SHOPPING_LIST_ITEM] = function (Container $container) {
            return SpyShoppingListItemQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_COMPANY_USER] = function (Container $container) {
            return SpyCompanyUserQuery::create();
        };

        return $container;
    }
}
