<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\WarehousesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;

class WarehouseUserAssignmentMapper implements WarehouseUserAssignmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer $warehouseUserAssignmentsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer
     */
    public function mapWarehouseUserAssignmentTransferToWarehouseUserAssignmentsBackendApiAttributesTransfer(
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer,
        WarehouseUserAssignmentsBackendApiAttributesTransfer $warehouseUserAssignmentsBackendApiAttributesTransfer
    ): WarehouseUserAssignmentsBackendApiAttributesTransfer {
        $warehouseUserAssignmentsBackendApiAttributesTransfer->fromArray($warehouseUserAssignmentTransfer->toArray(), true);
        $warehouseUserAssignmentsBackendApiAttributesTransfer->setWarehouse(
            $this->mapStockTransferToWarehousesBackendApiAttributesTransfer(
                $warehouseUserAssignmentTransfer->getWarehouseOrFail(),
                new WarehousesBackendApiAttributesTransfer(),
            ),
        );

        return $warehouseUserAssignmentsBackendApiAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer $warehouseUserAssignmentsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer
     */
    public function mapWarehouseUserAssignmentsBackendApiAttributesTransferToWarehouseUserAssignmentTransfer(
        WarehouseUserAssignmentsBackendApiAttributesTransfer $warehouseUserAssignmentsBackendApiAttributesTransfer,
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
    ): WarehouseUserAssignmentTransfer {
        $stockTransfer = $warehouseUserAssignmentTransfer->getWarehouse();
        if ($warehouseUserAssignmentsBackendApiAttributesTransfer->getWarehouse()) {
            $stockTransfer = $this->mapWarehousesBackendApiAttributesTransferToStockTransfer(
                $warehouseUserAssignmentsBackendApiAttributesTransfer->getWarehouseOrFail(),
                $stockTransfer ?? new StockTransfer(),
            );
        }
        $warehouseUserAssignmentTransfer->fromArray($warehouseUserAssignmentsBackendApiAttributesTransfer->modifiedToArray(), true);

        return $warehouseUserAssignmentTransfer->setWarehouse($stockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Generated\Shared\Transfer\WarehousesBackendApiAttributesTransfer $warehousesBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\WarehousesBackendApiAttributesTransfer
     */
    protected function mapStockTransferToWarehousesBackendApiAttributesTransfer(
        StockTransfer $stockTransfer,
        WarehousesBackendApiAttributesTransfer $warehousesBackendApiAttributesTransfer
    ): WarehousesBackendApiAttributesTransfer {
        return $warehousesBackendApiAttributesTransfer->fromArray($stockTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\WarehousesBackendApiAttributesTransfer $warehousesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    protected function mapWarehousesBackendApiAttributesTransferToStockTransfer(
        WarehousesBackendApiAttributesTransfer $warehousesBackendApiAttributesTransfer,
        StockTransfer $stockTransfer
    ): StockTransfer {
        return $stockTransfer->fromArray($warehousesBackendApiAttributesTransfer->modifiedToArray(), true);
    }
}
