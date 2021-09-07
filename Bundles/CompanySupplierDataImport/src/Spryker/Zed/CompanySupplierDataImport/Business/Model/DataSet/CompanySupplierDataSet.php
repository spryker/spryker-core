<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanySupplierDataImport\Business\Model\DataSet;

interface CompanySupplierDataSet
{
    /**
     * @var string
     */
    public const COMPANY_ID = 'company_id';
    /**
     * @var string
     */
    public const COMPANY_KEY = 'company_key';
    /**
     * @var string
     */
    public const COMPANY_TYPE = 'company_type';
    /**
     * @var string
     */
    public const CONCRETE_SKU = 'concrete_sku';
    /**
     * @var string
     */
    public const PRODUCT_ID = 'product_id';
    /**
     * @var string
     */
    public const STORE = 'store';
    /**
     * @var string
     */
    public const STORE_ID = 'store_id';
    /**
     * @var string
     */
    public const CURRENCY = 'currency';
    /**
     * @var string
     */
    public const CURRENCY_ID = 'currency_id';
    /**
     * @var string
     */
    public const PRICE_GROSS = 'value_gross';
    /**
     * @var string
     */
    public const PRICE_NET = 'value_net';
}
