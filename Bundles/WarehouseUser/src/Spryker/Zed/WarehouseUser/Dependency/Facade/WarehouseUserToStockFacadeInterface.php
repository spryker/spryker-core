<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Dependency\Facade;

use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockCriteriaFilterTransfer;

interface WarehouseUserToStockFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function getStocksByStockCriteriaFilter(StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): StockCollectionTransfer;
}
