<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStock;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;

/**
 * Inherited Methods
 *
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
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferStockBusinessTester extends Actor
{
    use _generated\ProductOfferStockBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param int $quantity
     * @param string $storeName
     * @param string $productOfferReference
     *
     * @return \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock
     */
    public function createProductOfferStock(
        int $quantity,
        string $storeName,
        string $productOfferReference
    ): SpyProductOfferStock {
        $stockTransfer = $this->createStock($storeName);

        $productOfferTransfer = $this->haveProductOffer([
            ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => $productOfferReference,
        ]);

        $productOfferStockEntity = (new SpyProductOfferStock())
            ->setQuantity($quantity)
            ->setFkProductOffer($productOfferTransfer->getIdProductOffer())
            ->setFkStock($stockTransfer->getIdStock());

        $productOfferStockEntity->save();

        return $productOfferStockEntity;
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    protected function createStock(string $storeName): StockTransfer
    {
        $storeTransfer = $this->haveStore([
            StoreTransfer::NAME => $storeName,
        ]);

        $stockTransfer = $this->haveStock();

        $this->haveStockStoreRelation(
            $stockTransfer,
            $storeTransfer
        );

        return $stockTransfer;
    }

    /**
     * @return void
     */
    public function truncateProductOffers(): void
    {
        $this->truncateTableRelations($this->getProductOfferQuery());
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }
}
