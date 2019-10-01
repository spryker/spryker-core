<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Stock\Persistence\SpyStock;

class StockStoreRelationMapper
{
    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapStockStoreEntityToStoreRelationTransfer(SpyStock $stockEntity, StoreRelationTransfer $storeRelationTransfer): StoreRelationTransfer
    {
        $storeTransfers = $this->mapStoreTransfers($stockEntity);
        $idStores = $this->selectIdStores($storeTransfers);

        $storeRelationTransfer
            ->setIdEntity($stockEntity->getIdStock())
            ->setStores(new ArrayObject($storeTransfers))
            ->setIdStores($idStores);

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function mapStoreTransfers(SpyStock $stockEntity): array
    {
        $storeTransfers = [];
        foreach ($stockEntity->getSpyStockStores() as $stockStoreEntity) {
            $storeTransfers[] = (new StoreTransfer())->fromArray($stockStoreEntity->getStore()->toArray(), true);
        }

        return $storeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return int[]
     */
    protected function selectIdStores(array $storeTransfers): array
    {
        return array_map(function (StoreTransfer $storeTransfer): int {
            return $storeTransfer->getIdStore();
        }, $storeTransfers);
    }
}
