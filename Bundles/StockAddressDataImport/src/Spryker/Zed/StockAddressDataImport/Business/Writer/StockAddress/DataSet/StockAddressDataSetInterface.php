<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress\DataSet;

interface StockAddressDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_WAREHOUSE_NAME = 'warehouse_name';
    /**
     * @var string
     */
    public const COLUMN_ADDRESS1 = 'address1';
    /**
     * @var string
     */
    public const COLUMN_ADDRESS2 = 'address2';
    /**
     * @var string
     */
    public const COLUMN_ADDRESS3 = 'address3';
    /**
     * @var string
     */
    public const COLUMN_CITY = 'city';
    /**
     * @var string
     */
    public const COLUMN_ZIP_CODE = 'zip_code';
    /**
     * @var string
     */
    public const COLUMN_REGION_NAME = 'region_name';
    /**
     * @var string
     */
    public const COLUMN_COUNTRY_ISO2_CODE = 'country_iso2_code';
    /**
     * @var string
     */
    public const COLUMN_PHONE = 'phone';
    /**
     * @var string
     */
    public const COLUMN_COMMENT = 'comment';

    /**
     * @var string
     */
    public const ID_STOCK = 'id_stock';
    /**
     * @var string
     */
    public const ID_COUNTRY = 'id_country';
    /**
     * @var string
     */
    public const ID_REGION = 'id_region';
}
