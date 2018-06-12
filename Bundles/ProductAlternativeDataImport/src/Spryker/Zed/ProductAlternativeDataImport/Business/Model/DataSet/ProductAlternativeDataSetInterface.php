<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductAlternativeDataImport\Business\Model\DataSet;

interface ProductAlternativeDataSetInterface
{
    public const KEY_COLUMN_CONCRETE_SKU = 'concrete_sku';
    public const KEY_COLUMN_PRODUCT_ID = 'product_id';
    public const KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU = 'alternative_product_concrete_sku';
    public const KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_ID = 'alternative_product_concrete_id';
    public const KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU = 'alternative_product_abstract_sku';
    public const KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_ID = 'alternative_product_abstract_id';
}
