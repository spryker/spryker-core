<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\StockCollectionTransfer;

interface WarehouseReaderInterface
{
    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function getStockCollection(ArrayObject $warehouseUserAssignmentTransfers): StockCollectionTransfer;
}
