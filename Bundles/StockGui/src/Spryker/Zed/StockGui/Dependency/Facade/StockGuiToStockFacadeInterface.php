<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Dependency\Facade;

use Generated\Shared\Transfer\StockTransfer;

interface StockGuiToStockFacadeInterface
{
    /**
     * @return array
     */
    public function getWarehouseToStoreMapping();

    /**
     * @return array
     */
    public function getStoreToWarehouseMapping();

    /**
     * @param int $idStock
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findStockById(int $idStock): ?StockTransfer;
}
