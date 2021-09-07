<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model;

interface ProductMeasurementSalesUnitDataSet
{
    /**
     * @var string
     */
    public const COLUMN_SALES_UNIT_KEY = 'sales_unit_key';
    /**
     * @var string
     */
    public const COLUMN_CONCRETE_SKU = 'concrete_sku';
    /**
     * @var string
     */
    public const COLUMN_CODE = 'code';
    /**
     * @var string
     */
    public const COLUMN_CONVERSION = 'conversion';
    /**
     * @var string
     */
    public const COLUMN_PRECISION = 'precision';
    /**
     * @var string
     */
    public const COLUMN_IS_DISPLAYED = 'is_displayed';
    /**
     * @var string
     */
    public const COLUMN_IS_DEFAULT = 'is_default';
}
