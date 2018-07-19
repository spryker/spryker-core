<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\DataSet;

interface ProductPackagingUnitDataSetInterface
{
    public const COLUMN_CONCRETE_SKU = 'concrete_sku';
    public const COLUMN_IS_LEAD_PRODUCT = 'is_lead_product';
    public const COLUMN_HAS_LEAD_PRODUCT = 'has_lead_product';
    public const COLUMN_TYPE_NAME = 'packaging_unit_type_name';
    public const COLUMN_DEFAULT_AMOUNT = 'default_amount';
    public const COLUMN_IS_VARIABLE = 'is_variable';
    public const COLUMN_AMOUNT_MIN = 'amount_min';
    public const COLUMN_AMOUNT_MAX = 'amount_max';
    public const COLUMN_AMOUNT_INTERVAL = 'amount_interval';
}
