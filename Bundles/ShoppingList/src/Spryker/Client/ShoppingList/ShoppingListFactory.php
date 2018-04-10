<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingList;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShoppingList\Cart\CartHandler;
use Spryker\Client\ShoppingList\Cart\CartHandlerInterface;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCartClientInterface;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCustomerClientInterface;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToPriceProductClientInterface;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToProductClientInterface;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToZedRequestClientInterface;
use Spryker\Client\ShoppingList\PermissionUpdater\PermissionUpdater;
use Spryker\Client\ShoppingList\PermissionUpdater\PermissionUpdaterInterface;
use Spryker\Client\ShoppingList\Product\ProductStorage;
use Spryker\Client\ShoppingList\Product\ProductStorageInterface;
use Spryker\Client\ShoppingList\Zed\ShoppingListStub;
use Spryker\Client\ShoppingList\Zed\ShoppingListStubInterface;

class ShoppingListFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ShoppingList\Zed\ShoppingListStubInterface
     */
    public function createShoppingListStub(): ShoppingListStubInterface
    {
        return new ShoppingListStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ShoppingList\Product\ProductStorageInterface
     */
    public function createProductStorage(): ProductStorageInterface
    {
        return new ProductStorage(
            $this->getProductClient(),
            $this->getPriceProductClient()
        );
    }

    /**
     * @return \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToProductClientInterface
     */
    public function getProductClient(): ShoppingListToProductClientInterface
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \Spryker\Client\ShoppingList\Cart\CartHandlerInterface
     */
    public function createCartHandler(): CartHandlerInterface
    {
        return new CartHandler(
            $this->getCartClient(),
            $this->createShoppingListStub()
        );
    }

    /**
     * @return \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCustomerClientInterface
     */
    public function getCustomerClient(): ShoppingListToCustomerClientInterface
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToPriceProductClientInterface
     */
    public function getPriceProductClient(): ShoppingListToPriceProductClientInterface
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToZedRequestClientInterface
     */
    public function getZedRequestClient(): ShoppingListToZedRequestClientInterface
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToCartClientInterface
     */
    public function getCartClient(): ShoppingListToCartClientInterface
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Client\ShoppingList\PermissionUpdater\PermissionUpdaterInterface
     */
    public function createPermissionUpdater(): PermissionUpdaterInterface
    {
        return new PermissionUpdater($this->getCustomerClient());
    }
}
