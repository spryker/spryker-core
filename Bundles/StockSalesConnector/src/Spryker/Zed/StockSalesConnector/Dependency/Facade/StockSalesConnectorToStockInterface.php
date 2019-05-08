<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockSalesConnector\Dependency\Facade;

use Generated\Shared\Transfer\StockProductTransfer;

interface StockSalesConnectorToStockInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $transferStockProduct);

    /**
     * @param string $sku
     * @param string $stockType
     * @param float $decrementBy
     *
     * @return void
     */
    public function decrementStockProduct($sku, $stockType, $decrementBy = 1.0);

    /**
     * @param string $sku
     * @param string $stockType
     * @param float $incrementBy
     *
     * @return void
     */
    public function incrementStockProduct($sku, $stockType, $incrementBy = 1.0);
}
