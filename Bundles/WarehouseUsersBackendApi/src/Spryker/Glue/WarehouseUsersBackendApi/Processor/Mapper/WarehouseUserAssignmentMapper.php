<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiWarehousesAttributesTransfer;
use Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;

class WarehouseUserAssignmentMapper implements WarehouseUserAssignmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     * @param \Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer
     */
    public function mapWarehouseUserAssignmentTransferToApiWarehouseUserAssignmentsAttributesTransfer(
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer,
        ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer
    ): ApiWarehouseUserAssignmentsAttributesTransfer {
        $apiWarehouseUserAssignmentsAttributesTransfer->fromArray($warehouseUserAssignmentTransfer->toArray(), true);
        $apiWarehouseUserAssignmentsAttributesTransfer->setWarehouse(
            $this->mapStockTransferToApiWarehousesAttributesTransfer(
                $warehouseUserAssignmentTransfer->getWarehouseOrFail(),
                new ApiWarehousesAttributesTransfer(),
            ),
        );

        return $apiWarehouseUserAssignmentsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer
     */
    public function mapApiWarehouseUserAssignmentsAttributesTransferToWarehouseUserAssignmentTransfer(
        ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer,
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
    ): WarehouseUserAssignmentTransfer {
        $warehouseUserAssignmentTransfer->fromArray($apiWarehouseUserAssignmentsAttributesTransfer->modifiedToArray(), true);
        if ($apiWarehouseUserAssignmentsAttributesTransfer->getWarehouse()) {
            $warehouseUserAssignmentTransfer->setWarehouse(
                $this->mapApiWarehousesAttributesTransferToStockTransfer(
                    $apiWarehouseUserAssignmentsAttributesTransfer->getWarehouseOrFail(),
                    new StockTransfer(),
                ),
            );
        }

        return $warehouseUserAssignmentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Generated\Shared\Transfer\ApiWarehousesAttributesTransfer $apiWarehousesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiWarehousesAttributesTransfer
     */
    protected function mapStockTransferToApiWarehousesAttributesTransfer(
        StockTransfer $stockTransfer,
        ApiWarehousesAttributesTransfer $apiWarehousesAttributesTransfer
    ): ApiWarehousesAttributesTransfer {
        return $apiWarehousesAttributesTransfer->fromArray($stockTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiWarehousesAttributesTransfer $apiWarehousesAttributesTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    protected function mapApiWarehousesAttributesTransferToStockTransfer(
        ApiWarehousesAttributesTransfer $apiWarehousesAttributesTransfer,
        StockTransfer $stockTransfer
    ): StockTransfer {
        return $stockTransfer->fromArray($apiWarehousesAttributesTransfer->toArray(), true);
    }
}
