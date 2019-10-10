<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Stock\Persistence\StockEntityManagerInterface;
use Spryker\Zed\Stock\Persistence\StockRepositoryInterface;

class StockStoreRelationshipUpdater implements StockStoreRelationshipUpdaterInterface
{
    /**
     * @var \Spryker\Zed\Stock\Persistence\StockRepositoryInterface
     */
    protected $stockRepository;

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockEntityManagerInterface
     */
    protected $stockEntityManager;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockRepositoryInterface $stockRepository
     * @param \Spryker\Zed\Stock\Persistence\StockEntityManagerInterface $stockEntityManager
     */
    public function __construct(StockRepositoryInterface $stockRepository, StockEntityManagerInterface $stockEntityManager)
    {
        $this->stockRepository = $stockRepository;
        $this->stockEntityManager = $stockEntityManager;
    }

    /**
     * @param int $idStock
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $storeRelationTransfer
     *
     * @return void
     */
    public function updateStockStoreRelationshipsForStock(int $idStock, ?StoreRelationTransfer $storeRelationTransfer): void
    {
        if ($storeRelationTransfer === null) {
            return;
        }

        $storeRelationTransfer->requireIdStores();

        $originalStoreRelationTransfer = $this->stockRepository->getStoreRelationByIdStock($idStock);
        $deleteStoreIds = array_diff($originalStoreRelationTransfer->getIdStores(), $storeRelationTransfer->getIdStores());
        $addStoreIds = array_diff($storeRelationTransfer->getIdStores(), $originalStoreRelationTransfer->getIdStores());

        $this->stockEntityManager->deleteStockStoreRelations($idStock, $deleteStoreIds);
        $this->stockEntityManager->addStockStoreRelations($idStock, $addStoreIds);
    }
}
