<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\DataSet;

interface ShoppingListItemDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_SHOPPING_LIST_KEY = 'shopping_list_key';

    /**
     * @var string
     */
    public const COLUMN_PRODUCT_SKU = 'product_sku';

    /**
     * @var string
     */
    public const COLUMN_QUANTITY = 'quantity';

    /**
     * @var string
     */
    public const ID_SHOPPING_LIST = 'id_shopping_list';

    /**
     * @var string
     */
    public const ID_COMPANY_USER = 'id_company_user';
}
