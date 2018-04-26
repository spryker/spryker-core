<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityDataImport\Business\Model;

interface ProductQuantityDataImportDataSet
{
    public const KEY_CONCRETE_SKU = 'concrete_sku';
    public const KEY_QUANTITY_MIN = 'quantity_min';
    public const KEY_QUANTITY_MAX = 'quantity_max';
    public const KEY_QUANTITY_INTERVAL = 'quantity_interval';

    public const DEFAULT_MAX = null;
    public const DEFAULT_INTERVAL = 1;
}
