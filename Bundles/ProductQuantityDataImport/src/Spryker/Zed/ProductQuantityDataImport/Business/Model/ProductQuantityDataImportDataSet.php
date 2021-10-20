<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductQuantityDataImport\Business\Model;

interface ProductQuantityDataImportDataSet
{
    /**
     * @var string
     */
    public const COLUMN_CONCRETE_SKU = 'concrete_sku';

    /**
     * @var string
     */
    public const COLUMN_QUANTITY_MIN = 'quantity_min';

    /**
     * @var string
     */
    public const COLUMN_QUANTITY_MAX = 'quantity_max';

    /**
     * @var string
     */
    public const COLUMN_QUANTITY_INTERVAL = 'quantity_interval';
}
