<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehousesBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\WarehouseResourceCollectionTransfer;
use Generated\Shared\Transfer\WarehousesBackendApiAttributesTransfer;
use Spryker\Glue\WarehousesBackendApi\WarehousesBackendApiConfig;

class WarehouseResourceMapper implements WarehouseResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockCollectionTransfer $stockCollectionTransfer
     * @param \Generated\Shared\Transfer\WarehouseResourceCollectionTransfer $warehouseResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseResourceCollectionTransfer
     */
    public function mapStockCollectionToWarehouseResourceCollection(
        StockCollectionTransfer $stockCollectionTransfer,
        WarehouseResourceCollectionTransfer $warehouseResourceCollectionTransfer
    ): WarehouseResourceCollectionTransfer {
        foreach ($stockCollectionTransfer->getStocks() as $stockTransfer) {
            $warehouseResourceCollectionTransfer->addWarehouseResource(
                $this->mapStockTransferToGlueResourceTransfer($stockTransfer, new GlueResourceTransfer()),
            );
        }

        return $warehouseResourceCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function mapStockTransferToGlueResourceTransfer(
        StockTransfer $stockTransfer,
        GlueResourceTransfer $glueResourceTransfer
    ): GlueResourceTransfer {
        $warehousesBackendApiAttributesTransfer = $this->mapStockTransferToApiWarehouseAttributesTransfer(
            $stockTransfer,
            new WarehousesBackendApiAttributesTransfer(),
        );

        return $glueResourceTransfer
            ->setType(WarehousesBackendApiConfig::RESOURCE_WAREHOUSES)
            ->setId($stockTransfer->getUuid())
            ->setAttributes($warehousesBackendApiAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Generated\Shared\Transfer\WarehousesBackendApiAttributesTransfer $warehousesBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\WarehousesBackendApiAttributesTransfer
     */
    protected function mapStockTransferToApiWarehouseAttributesTransfer(
        StockTransfer $stockTransfer,
        WarehousesBackendApiAttributesTransfer $warehousesBackendApiAttributesTransfer
    ): WarehousesBackendApiAttributesTransfer {
        return $warehousesBackendApiAttributesTransfer->fromArray($stockTransfer->toArray(), true);
    }
}
