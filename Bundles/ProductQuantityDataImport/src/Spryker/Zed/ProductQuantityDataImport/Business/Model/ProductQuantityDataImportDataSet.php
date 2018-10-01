<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductQuantityDataImport\Business\Model;

interface ProductQuantityDataImportDataSet
{
    public const COLUMN_CONCRETE_SKU = 'concrete_sku';
    public const COLUMN_QUANTITY_MIN = 'quantity_min';
    public const COLUMN_QUANTITY_MAX = 'quantity_max';
    public const COLUMN_QUANTITY_INTERVAL = 'quantity_interval';
}
