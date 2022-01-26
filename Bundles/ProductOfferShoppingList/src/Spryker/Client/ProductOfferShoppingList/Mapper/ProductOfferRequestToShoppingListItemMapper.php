<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShoppingList\Mapper;

use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ProductOfferRequestToShoppingListItemMapper implements ProductOfferRequestToShoppingListItemMapperInterface
{
    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_OFFER_REFERENCE = 'productOfferReference';

    /**
     * @param array<string, string> $params
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function map(array $params, ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        if (isset($params[static::PARAMETER_PRODUCT_OFFER_REFERENCE])) {
            $shoppingListItemTransfer->setProductOfferReference($params[static::PARAMETER_PRODUCT_OFFER_REFERENCE]);
        }

        return $shoppingListItemTransfer;
    }
}
