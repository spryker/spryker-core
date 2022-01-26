<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShoppingList;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOfferShoppingList\Mapper\ProductOfferRequestToShoppingListItemMapper;
use Spryker\Client\ProductOfferShoppingList\Mapper\ProductOfferRequestToShoppingListItemMapperInterface;
use Spryker\Client\ProductOfferShoppingList\Mapper\ProductOfferShoppingListItemToItemMapper;

/**
 * @method \Spryker\Client\ProductOfferShoppingList\ProductOfferShoppingListConfig getConfig()
 */
class ProductOfferShoppingListFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductOfferShoppingList\Mapper\ProductOfferShoppingListItemToItemMapper
     */
    public function createProductOfferShoppingListItemToItemMapper(): ProductOfferShoppingListItemToItemMapper
    {
        return new ProductOfferShoppingListItemToItemMapper();
    }

    /**
     * @return \Spryker\Client\ProductOfferShoppingList\Mapper\ProductOfferRequestToShoppingListItemMapperInterface
     */
    public function createProductOfferRequestToShoppingListItemMapper(): ProductOfferRequestToShoppingListItemMapperInterface
    {
        return new ProductOfferRequestToShoppingListItemMapper();
    }
}
