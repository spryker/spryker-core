<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress\DataSet;

interface StockAddressDataSetInterface
{
    public const COLUMN_WAREHOUSE_NAME = 'warehouse_name';
    public const COLUMN_ADDRESS1 = 'address1';
    public const COLUMN_ADDRESS2 = 'address2';
    public const COLUMN_ADDRESS3 = 'address3';
    public const COLUMN_CITY = 'city';
    public const COLUMN_ZIP_CODE = 'zip_code';
    public const COLUMN_REGION_NAME = 'region_name';
    public const COLUMN_COUNTRY_ISO2_CODE = 'country_iso2_code';
    public const COLUMN_PHONE = 'phone';
    public const COLUMN_COMMENT = 'comment';

    public const ID_STOCK = 'id_stock';
    public const ID_COUNTRY = 'id_country';
    public const ID_REGION = 'id_region';
}
