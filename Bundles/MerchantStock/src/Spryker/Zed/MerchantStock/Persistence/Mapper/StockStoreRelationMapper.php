<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStore;

class StockStoreRelationMapper
{
    /**
     * @param int $idStock
     * @param \Orm\Zed\Stock\Persistence\SpyStockStore[] $stockStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapStockStoreEntitiesToStoreRelationTransfer(
        int $idStock,
        array $stockStoreEntities,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        $storeTransfers = $this->mapStoreTransfers($stockStoreEntities);
        $storeIds = $this->getStoreIdsFromStoreTransferCollection($storeTransfers);

        $storeRelationTransfer
            ->setIdEntity($idStock)
            ->setStores(new ArrayObject($storeTransfers))
            ->setIdStores($storeIds);

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockStore[] $stockStoreEntities
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function mapStoreTransfers(array $stockStoreEntities): array
    {
        $storeTransfers = [];
        foreach ($stockStoreEntities as $stockStoreEntity) {
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
    protected function getStoreIdsFromStoreTransferCollection(array $storeTransfers): array
    {
        return array_map(function (StoreTransfer $storeTransfer): int {
            return $storeTransfer->getIdStore();
        }, $storeTransfers);
    }
}
