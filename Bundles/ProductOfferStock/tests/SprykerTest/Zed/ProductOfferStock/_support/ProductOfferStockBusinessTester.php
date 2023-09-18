<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStock;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery;
use Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepository;
use Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\ProductOfferStock\Business\ProductOfferStockFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductOfferStock\PHPMD)
 */
class ProductOfferStockBusinessTester extends Actor
{
    use _generated\ProductOfferStockBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureProductOfferStockTableIsEmpty(): void
    {
        $query = $this->getProductOfferStockQuery();
        $this->ensureDatabaseTableIsEmpty($query);
        $query->deleteAll();
    }

    /**
     * @return \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStockQuery
     */
    public function getProductOfferStockQuery(): SpyProductOfferStockQuery
    {
        return SpyProductOfferStockQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface
     */
    public function getProductOfferStockRepository(): ProductOfferStockRepositoryInterface
    {
        return new ProductOfferStockRepository();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer
     */
    public function haveProductOfferStockWithProductOfferAndStoreAttached(
        ProductOfferTransfer $productOfferTransfer,
        StoreTransfer $storeTransfer
    ): ProductOfferStockTransfer {
        return $this->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOffer(),
            ProductOfferStockTransfer::STOCK => [
                StockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => [
                        $storeTransfer->getIdStore(),
                    ],
                ],
            ],
        ]);
    }
}
