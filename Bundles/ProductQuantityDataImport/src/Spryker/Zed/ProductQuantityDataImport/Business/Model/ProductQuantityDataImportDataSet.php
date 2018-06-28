<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityDataImport\Business\Model;

interface ProductQuantityDataImportDataSet
{
    public const COLUMN_CONCRETE_SKU = 'concrete_sku';
    public const COLUMN_QUANTITY_MIN = 'quantity_min';
    public const COLUMN_QUANTITY_MAX = 'quantity_max';
    public const COLUMN_QUANTITY_INTERVAL = 'quantity_interval';
}
