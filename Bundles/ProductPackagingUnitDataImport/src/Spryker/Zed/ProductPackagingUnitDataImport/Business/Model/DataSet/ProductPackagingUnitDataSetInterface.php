<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\DataSet;

interface ProductPackagingUnitDataSetInterface
{
    public const COLUMN_CONCRETE_SKU = 'concrete_sku';
    public const COLUMN_LEAD_PRODUCT_SKU = 'lead_product_sku';
    public const COLUMN_TYPE_NAME = 'packaging_unit_type_name';
    public const COLUMN_DEFAULT_AMOUNT = 'default_amount';
    public const COLUMN_IS_VARIABLE = 'is_variable';
    public const COLUMN_AMOUNT_MIN = 'amount_min';
    public const COLUMN_AMOUNT_MAX = 'amount_max';
    public const COLUMN_AMOUNT_INTERVAL = 'amount_interval';
}
