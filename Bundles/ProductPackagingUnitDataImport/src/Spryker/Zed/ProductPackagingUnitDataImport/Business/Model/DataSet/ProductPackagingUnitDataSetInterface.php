<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\DataSet;

interface ProductPackagingUnitDataSetInterface
{
    public const CONCRETE_SKU = 'concrete_sku';
    public const IS_LEAD_PRODUCT = 'is_lead_product';
    public const HAS_LEAD_PRODUCT = 'has_lead_product';
    public const TYPE_NAME = 'packaging_unit_type_name';
    public const DEFAULT_AMOUNT = 'default_amount';
    public const IS_VARIABLE = 'is_variable';
    public const AMOUNT_MIN = 'amount_min';
    public const AMOUNT_MAX = 'amount_max';
    public const AMOUNT_INTERVAL = 'amount_interval';
}
