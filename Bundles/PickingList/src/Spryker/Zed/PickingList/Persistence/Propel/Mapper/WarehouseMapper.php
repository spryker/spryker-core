<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\PickingList\Persistence\SpyPickingList;
use Orm\Zed\Stock\Persistence\SpyStock;

class WarehouseMapper
{
    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $warehouseEntity
     * @param \Generated\Shared\Transfer\StockTransfer $warehouseTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function mapWarehouseEntityToWarehouseTransfer(
        SpyStock $warehouseEntity,
        StockTransfer $warehouseTransfer
    ): StockTransfer {
        return $warehouseTransfer->fromArray($warehouseEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     * @param \Orm\Zed\PickingList\Persistence\SpyPickingList $pickingListEntity
     *
     * @return \Orm\Zed\PickingList\Persistence\SpyPickingList
     */
    public function mapWarehouseToPickingListEntity(
        PickingListTransfer $pickingListTransfer,
        SpyPickingList $pickingListEntity
    ): SpyPickingList {
        $pickingListEntity->setFkWarehouse(
            $pickingListTransfer->getWarehouseOrFail()->getIdStockOrFail(),
        );

        return $pickingListEntity;
    }
}
