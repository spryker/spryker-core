<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class StockConfig extends AbstractBundleConfig
{
    public const TOUCH_STOCK_TYPE = 'stock-type';
    public const TOUCH_STOCK_PRODUCT = 'stock-product';

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
     * @return array
     */
    public function getStoreToWarehouseMapping()
    {
        return [
            'DE' => [
                'Warehouse1',
                'Warehouse2',
            ],
            'AT' => [
                'Warehouse1',
            ],
        ];
    }
}
