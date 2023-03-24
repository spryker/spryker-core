<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\StockConditionsTransfer;
use Generated\Shared\Transfer\StockCriteriaTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToStockFacadeInterface;

class StockReader implements StockReaderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToStockFacadeInterface
     */
    protected PickingListsBackendApiToStockFacadeInterface $stockFacade;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\Dependency\Facade\PickingListsBackendApiToStockFacadeInterface $stockFacade
     */
    public function __construct(PickingListsBackendApiToStockFacadeInterface $stockFacade)
    {
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function getStockTransfer(GlueRequestTransfer $glueRequestTransfer): ?StockTransfer
    {
        $idStock = $glueRequestTransfer->getRequestWarehouseOrFail()->getIdWarehouseOrFail();
        $stockCriteriaFilterTransfer = (new StockCriteriaTransfer())->setStockConditions(
            (new StockConditionsTransfer())->addIdStock($idStock),
        );

        $stockCollectionTransfer = $this->stockFacade->getStockCollection($stockCriteriaFilterTransfer);

        /** @var \ArrayObject<\Generated\Shared\Transfer\StockTransfer> $stockTransfers */
        $stockTransfers = $stockCollectionTransfer->getStocks();

        if (!$stockTransfers->count()) {
            return null;
        }

        return $stockTransfers->getIterator()->current();
    }
}
