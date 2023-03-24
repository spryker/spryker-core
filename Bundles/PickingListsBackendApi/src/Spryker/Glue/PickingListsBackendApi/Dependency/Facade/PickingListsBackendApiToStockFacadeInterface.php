<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockCriteriaTransfer;

interface PickingListsBackendApiToStockFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockCriteriaTransfer $stockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function getStockCollection(StockCriteriaTransfer $stockCriteriaTransfer): StockCollectionTransfer;
}
