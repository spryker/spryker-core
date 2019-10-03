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
use Orm\Zed\Store\Persistence\SpyStore;

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
        $idStores = $this->getIdStoresFromStoreTransferCollection($storeTransfers);

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
        foreach ($stockEntity->getStockStores() as $stockStoreEntity) {
            $storeTransfers[] = $this->mapStoreEntityToStoreTransfer($stockStoreEntity->getStore(), new StoreTransfer());
        }

        return $storeTransfers;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToStoreTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer
    {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return int[]
     */
    protected function getIdStoresFromStoreTransferCollection(array $storeTransfers): array
    {
        return array_map(function (StoreTransfer $storeTransfer): int {
            return $storeTransfer->getIdStore();
        }, $storeTransfers);
    }
}
