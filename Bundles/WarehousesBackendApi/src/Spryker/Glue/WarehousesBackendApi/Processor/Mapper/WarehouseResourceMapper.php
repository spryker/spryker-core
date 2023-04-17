<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehousesBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiWarehousesAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\WarehouseResourceCollectionTransfer;
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
        $apiWarehousesAttributesTransfer = $this->mapStockTransferToApiWarehouseAttributesTransfer(
            $stockTransfer,
            new ApiWarehousesAttributesTransfer(),
        );

        return $glueResourceTransfer
            ->setType(WarehousesBackendApiConfig::RESOURCE_WAREHOUSES)
            ->setId($stockTransfer->getUuid())
            ->setAttributes($apiWarehousesAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Generated\Shared\Transfer\ApiWarehousesAttributesTransfer $apiWarehousesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiWarehousesAttributesTransfer
     */
    protected function mapStockTransferToApiWarehouseAttributesTransfer(
        StockTransfer $stockTransfer,
        ApiWarehousesAttributesTransfer $apiWarehousesAttributesTransfer
    ): ApiWarehousesAttributesTransfer {
        return $apiWarehousesAttributesTransfer->fromArray($stockTransfer->toArray(), true);
    }
}
