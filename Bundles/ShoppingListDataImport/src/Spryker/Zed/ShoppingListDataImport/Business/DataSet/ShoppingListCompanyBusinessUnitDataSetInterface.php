<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\DataSet;

interface ShoppingListCompanyBusinessUnitDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_SHOPPING_LIST_KEY = 'shopping_list_key';
    /**
     * @var string
     */
    public const COLUMN_COMPANY_BUSINESS_UNIT_KEY = 'business_unit_key';
    /**
     * @var string
     */
    public const COLUMN_PERMISSION_GROUP_NAME = 'permission_group_name';

    /**
     * @var string
     */
    public const ID_SHOPPING_LIST = 'id_shopping_list';
    /**
     * @var string
     */
    public const ID_COMPANY_BUSINESS_UNIT = 'id_company_business_unit';
    /**
     * @var string
     */
    public const ID_PERMISSION_GROUP = 'id_shopping_list_permission_group';
}
