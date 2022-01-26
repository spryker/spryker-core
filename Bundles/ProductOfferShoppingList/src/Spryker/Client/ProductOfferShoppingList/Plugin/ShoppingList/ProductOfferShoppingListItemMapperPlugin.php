<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShoppingList\Plugin\ShoppingList;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemMapperPluginInterface;

/**
 * @method \Spryker\Client\ProductOfferShoppingList\ProductOfferShoppingListFactory getFactory()
 */
class ProductOfferShoppingListItemMapperPlugin extends AbstractPlugin implements ShoppingListItemMapperPluginInterface
{
 /**
  * {@inheritDoc}
  * - Maps Product Offer data to `ShoppingListItemTransfer`.
  *
  * @api
  *
  * @param array<string, string> $params
  * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
  *
  * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
  */
    public function map(array $params, ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getFactory()
            ->createProductOfferRequestToShoppingListItemMapper()
            ->map($params, $shoppingListItemTransfer);
    }
}
