<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockConditionsTransfer;
use Generated\Shared\Transfer\StockCriteriaTransfer;
use Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeInterface;

class WarehouseReader implements WarehouseReaderInterface
{
    /**
     * @var \Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeInterface
     */
    protected WarehouseUserToStockFacadeInterface $stockFacade;

    /**
     * @param \Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeInterface $stockFacade
     */
    public function __construct(WarehouseUserToStockFacadeInterface $stockFacade)
    {
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function getStockCollection(ArrayObject $warehouseUserAssignmentTransfers): StockCollectionTransfer
    {
        $stockCriteriaTransfer = $this->createStockCriteriaTransfer(
            $warehouseUserAssignmentTransfers,
        );

        return $this->stockFacade->getStockCollection(
            $stockCriteriaTransfer,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \Generated\Shared\Transfer\StockCriteriaTransfer
     */
    protected function createStockCriteriaTransfer(ArrayObject $warehouseUserAssignmentTransfers): StockCriteriaTransfer
    {
        $stockConditionsTransfer = new StockConditionsTransfer();
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            $stockTransfer = $warehouseUserAssignmentTransfer->getWarehouse();
            if (!$stockTransfer) {
                continue;
            }

            $idStock = $stockTransfer->getIdStock();
            if ($idStock) {
                $stockConditionsTransfer->addIdStock($idStock);
            }

            $uuidStock = $stockTransfer->getUuid();
            if ($uuidStock) {
                $stockConditionsTransfer->addUuid($uuidStock);
            }
        }

        return (new StockCriteriaTransfer())
            ->setStockConditions($stockConditionsTransfer);
    }
}
