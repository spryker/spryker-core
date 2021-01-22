<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Stock\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\StockBuilder;
use Generated\Shared\DataBuilder\StockProductBuilder;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Orm\Zed\Stock\Persistence\SpyStockStoreQuery;
use Spryker\Zed\Stock\Business\StockFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class StockDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @deprecated Use {@link haveProductInStockForStore()} instead.
     *
     * @param array $seedData
     *
     * @return void
     */
    public function haveProductInStock(array $seedData = []): void
    {
        $stockFacade = $this->getStockFacade();

        if (!isset($seedData[StockProductTransfer::FK_STOCK])) {
            $stockTransfer = $this->haveStock();
            $seedData[StockProductTransfer::FK_STOCK] = $stockTransfer->getIdStock();
            $seedData[StockProductTransfer::STOCK_TYPE] = $stockTransfer->getName();
        }

        $stockProductTransfer = (new StockProductBuilder($seedData))->build();
        $idStockProduct = $stockFacade->createStockProduct($stockProductTransfer);

        $this->debug(sprintf(
            'Inserted StockProduct: %d of Concrete Product SKU: %s',
            $idStockProduct,
            $stockProductTransfer->getSku()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($idStockProduct) {
            $this->cleanUpStockProduct($idStockProduct);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array $seedData
     *
     * @return void
     */
    public function haveProductInStockForStore(StoreTransfer $storeTransfer, array $seedData = []): void
    {
        $stockTransfer = $this->haveStock([
            StockTransfer::STORE_RELATION => (new StoreRelationTransfer())->setIdStores([$storeTransfer->getIdStore()]),
        ]);

        $seedData[StockProductTransfer::FK_STOCK] = $stockTransfer->getIdStock();
        $seedData[StockProductTransfer::STOCK_TYPE] = $stockTransfer->getName();

        $this->createStockProduct($seedData);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function haveStock(array $seedData = []): StockTransfer
    {
        $stockTransfer = (new StockBuilder($seedData))->build();

        $stockEntity = SpyStockQuery::create()
            ->filterByName($stockTransfer->getName())
            ->findOneOrCreate();
        $stockEntity->save();

        $stockTransfer->fromArray($stockEntity->toArray());

        if ($stockTransfer->getStoreRelation()) {
            foreach ($stockTransfer->getStoreRelation()->getIdStores() as $idStore) {
                $this->haveStockStoreRelation(
                    $stockTransfer,
                    (new StoreTransfer())->setIdStore($idStore)
                );
            }
        }

        $this->debug(sprintf(
            'Inserted Stock: %d',
            $stockTransfer->getIdStock()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($stockEntity) {
            $stockEntity->delete();
        });

        return $stockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function haveStockStoreRelation(StockTransfer $stockTransfer, StoreTransfer $storeTransfer): StoreRelationTransfer
    {
        $stockTransfer->requireIdStock();
        $storeTransfer->requireIdStore();

        $stockStoreEntity = SpyStockStoreQuery::create()
            ->filterByFkStock($stockTransfer->getIdStock())
            ->filterByFkStore($storeTransfer->getIdStore())
            ->findOneOrCreate();

        $stockStoreEntity->save();

        $this->debug(sprintf(
            'Inserted Stock Store Relation: %d',
            $stockStoreEntity->getIdStockStore()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($stockStoreEntity) {
            $stockStoreEntity->delete();
        });

        return (new StoreRelationTransfer())
            ->setIdEntity($stockTransfer->getIdStock())
            ->setIdStores([$storeTransfer->getIdStore()])
            ->setStores(new ArrayObject([$storeTransfer]));
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer
     */
    public function haveStockProduct(array $seedData = []): StockProductTransfer
    {
        return $this->createStockProduct($seedData);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer
     */
    protected function createStockProduct(array $seedData): StockProductTransfer
    {
        $stockFacade = $this->getStockFacade();

        $stockProductTransfer = (new StockProductBuilder($seedData))->build();
        $idStockProduct = $stockFacade->createStockProduct($stockProductTransfer);

        $this->debug(sprintf(
            'Inserted StockProduct: %d of Concrete Product SKU: %s',
            $idStockProduct,
            $stockProductTransfer->getSku()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($idStockProduct) {
            $this->cleanUpStockProduct($idStockProduct);
        });

        return $stockProductTransfer->setIdStockProduct($idStockProduct);
    }

    /**
     * @return \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected function getStockFacade(): StockFacadeInterface
    {
        return $this->getLocator()->stock()->facade();
    }

    /**
     * @param int $idStockProduct
     *
     * @return void
     */
    protected function cleanUpStockProduct(int $idStockProduct): void
    {
        SpyStockProductQuery::create()
            ->filterByIdStockProduct($idStockProduct)
            ->delete();
    }
}
