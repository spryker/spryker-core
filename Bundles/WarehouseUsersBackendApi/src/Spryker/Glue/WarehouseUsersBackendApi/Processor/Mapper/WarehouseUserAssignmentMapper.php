<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\WarehousesRestAttributesTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;

class WarehouseUserAssignmentMapper implements WarehouseUserAssignmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer
     */
    public function mapWarehouseUserAssignmentTransferToWarehouseUserAssignmentsRestAttributesTransfer(
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer,
        WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer
    ): WarehouseUserAssignmentsRestAttributesTransfer {
        $warehouseUserAssignmentsRestAttributesTransfer->fromArray($warehouseUserAssignmentTransfer->toArray(), true);
        $warehouseUserAssignmentsRestAttributesTransfer->setWarehouse(
            $this->mapStockTransferToWarehousesRestAttributesTransfer(
                $warehouseUserAssignmentTransfer->getWarehouseOrFail(),
                new WarehousesRestAttributesTransfer(),
            ),
        );

        return $warehouseUserAssignmentsRestAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer
     */
    public function mapWarehouseUserAssignmentsRestAttributesTransferToWarehouseUserAssignmentTransfer(
        WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer,
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
    ): WarehouseUserAssignmentTransfer {
        $warehouseUserAssignmentTransfer->fromArray($warehouseUserAssignmentsRestAttributesTransfer->modifiedToArray(), true);
        if ($warehouseUserAssignmentsRestAttributesTransfer->getWarehouse()) {
            $warehouseUserAssignmentTransfer->setWarehouse(
                $this->mapWarehousesRestAttributesTransferToStockTransfer(
                    $warehouseUserAssignmentsRestAttributesTransfer->getWarehouseOrFail(),
                    new StockTransfer(),
                ),
            );
        }

        return $warehouseUserAssignmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Generated\Shared\Transfer\WarehousesRestAttributesTransfer $warehousesRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\WarehousesRestAttributesTransfer
     */
    protected function mapStockTransferToWarehousesRestAttributesTransfer(
        StockTransfer $stockTransfer,
        WarehousesRestAttributesTransfer $warehousesRestAttributesTransfer
    ): WarehousesRestAttributesTransfer {
        return $warehousesRestAttributesTransfer->fromArray($stockTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\WarehousesRestAttributesTransfer $warehousesRestAttributesTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    protected function mapWarehousesRestAttributesTransferToStockTransfer(
        WarehousesRestAttributesTransfer $warehousesRestAttributesTransfer,
        StockTransfer $stockTransfer
    ): StockTransfer {
        return $stockTransfer->fromArray($warehousesRestAttributesTransfer->toArray(), true);
    }
}
