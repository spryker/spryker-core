<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Persistence;

use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\Stock\Persistence\SpyStockStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Stock\Persistence\StockPersistenceFactory getFactory()
 */
class StockEntityManager extends AbstractEntityManager implements StockEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function saveStock(StockTransfer $stockTransfer): StockTransfer
    {
        $stockEntity = $this->getFactory()
            ->createStockQuery()
            ->filterByIdStock($stockTransfer->getIdStock())
            ->findOneOrCreate();

        $stockEntity = $this->getFactory()
            ->createStockMapper()
            ->mapStockTransferToStockEntity($stockTransfer, $stockEntity);

        $stockEntity->save();

        return $stockTransfer->setIdStock($stockEntity->getIdStock());
    }

    /**
     * @param int $idStock
     * @param int[] $storeIds
     *
     * @return void
     */
    public function addStockStoreRelations(int $idStock, array $storeIds): void
    {
        foreach ($storeIds as $idStore) {
            $stockStoreEntity = new SpyStockStore();
            $stockStoreEntity->setFkStock($idStock)
                ->setFkStore($idStore)
                ->save();
        }
    }

    /**
     * @param int $idStock
     * @param int[] $storeIds
     *
     * @return void
     */
    public function deleteStockStoreRelations(int $idStock, array $storeIds): void
    {
        if ($storeIds === []) {
            return;
        }

        $this->getFactory()
            ->createStockStoreQuery()
            ->filterByFkStock($idStock)
            ->filterByFkStore_In($storeIds)
            ->delete();
    }
}
