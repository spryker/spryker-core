<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShoppingList\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferShoppingList\Business\Checker\ProductOfferShoppingListChecker;
use Spryker\Zed\ProductOfferShoppingList\Business\Checker\ProductOfferShoppingListCheckerInterface;
use Spryker\Zed\ProductOfferShoppingList\Dependency\Facade\ProductOfferShoppingListToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferShoppingList\ProductOfferShoppingListDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferShoppingList\ProductOfferShoppingListConfig getConfig()
 */
class ProductOfferShoppingListBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferShoppingList\Business\Checker\ProductOfferShoppingListCheckerInterface
     */
    public function createProductOfferShoppingListChecker(): ProductOfferShoppingListCheckerInterface
    {
        return new ProductOfferShoppingListChecker(
            $this->getProductOfferFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferShoppingList\Dependency\Facade\ProductOfferShoppingListToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferShoppingListToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferShoppingListDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
