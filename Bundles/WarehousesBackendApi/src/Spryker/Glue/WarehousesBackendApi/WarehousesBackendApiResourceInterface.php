<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehousesBackendApi;

use Generated\Shared\Transfer\StockCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseResourceCollectionTransfer;

interface WarehousesBackendApiResourceInterface
{
    /**
     * Specification:
     * - Retrieves multiple warehouse resources by criteria.
     * - Returns the collection of warehouse rest resources.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockCriteriaTransfer $stockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseResourceCollectionTransfer
     */
    public function getWarehouseResourceCollection(StockCriteriaTransfer $stockCriteriaTransfer): WarehouseResourceCollectionTransfer;
}
