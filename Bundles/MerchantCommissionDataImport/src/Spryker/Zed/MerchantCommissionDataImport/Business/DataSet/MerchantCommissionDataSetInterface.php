<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantCommissionDataImport\Business\DataSet;

interface MerchantCommissionDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_KEY = 'key';

    /**
     * @var string
     */
    public const COLUMN_NAME = 'name';

    /**
     * @var string
     */
    public const COLUMN_DESCRIPTION = 'description';

    /**
     * @var string
     */
    public const COLUMN_VALID_FROM = 'valid_from';

    /**
     * @var string
     */
    public const COLUMN_VALID_TO = 'valid_to';

    /**
     * @var string
     */
    public const COLUMN_IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    public const COLUMN_AMOUNT = 'amount';

    /**
     * @var string
     */
    public const COLUMN_CALCULATOR_TYPE_PLUGIN = 'calculator_type_plugin';

    /**
     * @var string
     */
    public const COLUMN_MERCHANT_COMMISSION_GROUP_KEY = 'merchant_commission_group_key';

    /**
     * @var string
     */
    public const COLUMN_PRIORITY = 'priority';

    /**
     * @var string
     */
    public const COLUMN_ITEM_CONDITION = 'item_condition';

    /**
     * @var string
     */
    public const COLUMN_ORDER_CONDITION = 'order_condition';

    /**
     * @var string
     */
    public const ID_MERCHANT_COMMISSION_GROUP = 'id_merchant_commission_group';
}
