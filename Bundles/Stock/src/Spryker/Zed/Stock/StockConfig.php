<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Stock\Business\Exception\StockToStoreToWarehouseMappingMissingException;

class StockConfig extends AbstractBundleConfig
{
    const TOUCH_STOCK_TYPE = 'stock-type';
    const TOUCH_STOCK_PRODUCT = 'stock-product';

    /**
     * Store to warehouse mapping, so that stock updates knows how to update availability
     * for example:
     *
     * return [
     *       //the key is store name as defined in stores.php
     *       'DE' => [
     *           'Warehouse1', //the name of warehouse as is in spy_stock.name table.
     *           'Warehouse2'
     *         ],
     *       'AT' => [
     *           'Warehouse2'
     *       ],
     *       'US' => [
     *           'Warehouse2'
     *       ],
     * ];
     *
     */
    public function getStoreToWarehouseMapping()
    {
        throw new StockToStoreToWarehouseMappingMissingException(
            sprintf(
                'Store to warehouse mapping is not provided. Provide configuration in project StockConfig file by extending getStoreToWarehouseMapping() core method.'
            )
        );
    }
}
