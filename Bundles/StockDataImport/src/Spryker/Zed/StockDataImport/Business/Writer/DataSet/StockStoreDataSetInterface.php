<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockDataImport\Business\Writer\DataSet;

interface StockStoreDataSetInterface
{
    public const COLUMN_WAREHOUSE_NAME = 'warehouse_name';
    public const COLUMN_STORE_NAME = 'store_name';

    public const COLUMN_ID_STORE = 'fk_store';
    public const COLUMN_ID_STOCK = 'fk_stock';
}
