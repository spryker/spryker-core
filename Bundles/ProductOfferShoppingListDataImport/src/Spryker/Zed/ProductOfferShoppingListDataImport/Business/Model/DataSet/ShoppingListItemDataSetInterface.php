<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShoppingListDataImport\Business\Model\DataSet;

interface ShoppingListItemDataSetInterface
{
    /**
     * @var string
     *
     * @see \Spryker\Zed\ShoppingListDataImport\Business\DataSet\ShoppingListItemDataSetInterface::COLUMN_SHOPPING_LIST_KEY
     */
    public const COLUMN_SHOPPING_LIST_ITEM_KEY = 'shopping_list_item_key';

    /**
     * @var string
     */
    public const COLUMN_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
}
