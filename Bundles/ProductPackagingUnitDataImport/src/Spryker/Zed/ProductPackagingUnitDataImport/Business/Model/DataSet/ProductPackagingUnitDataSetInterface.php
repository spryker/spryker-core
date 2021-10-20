<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\DataSet;

interface ProductPackagingUnitDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_CONCRETE_SKU = 'concrete_sku';

    /**
     * @var string
     */
    public const COLUMN_LEAD_PRODUCT_SKU = 'lead_product_sku';

    /**
     * @var string
     */
    public const COLUMN_TYPE_NAME = 'packaging_unit_type_name';

    /**
     * @var string
     */
    public const COLUMN_DEFAULT_AMOUNT = 'default_amount';

    /**
     * @var string
     */
    public const COLUMN_IS_AMOUNT_VARIABLE = 'is_amount_variable';

    /**
     * @var string
     */
    public const COLUMN_AMOUNT_MIN = 'amount_min';

    /**
     * @var string
     */
    public const COLUMN_AMOUNT_MAX = 'amount_max';

    /**
     * @var string
     */
    public const COLUMN_AMOUNT_INTERVAL = 'amount_interval';
}
