<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailability;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservation;
use Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock;
use Orm\Zed\Store\Persistence\SpyStoreQuery;

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
class ProductOfferAvailabilityBusinessTester extends Actor
{
    use _generated\ProductOfferAvailabilityBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param int $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Orm\Zed\ProductOfferStock\Persistence\SpyProductOfferStock
     */
    public function createProductOfferStock(
        int $quantity,
        StoreTransfer $storeTransfer,
        ProductOfferTransfer $productOfferTransfer
    ): SpyProductOfferStock {
        $stockTransfer = $this->createStockForStore($storeTransfer);

        $productOfferStockEntity = (new SpyProductOfferStock())
            ->setQuantity($quantity)
            ->setFkProductOffer($productOfferTransfer->getIdProductOffer())
            ->setFkStock($stockTransfer->getIdStock());

        $productOfferStockEntity->save();

        return $productOfferStockEntity;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function createStore(): StoreTransfer
    {
        return $this->haveStore();
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOfferForSku(string $sku): ProductOfferTransfer
    {
        return $this->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $sku,
        ]);
    }

    /**
     * @param string $quantity
     * @param string $storeName
     * @param string $sku
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservation
     */
    public function createOmsProductReservation(string $quantity, string $storeName, string $sku): SpyOmsProductReservation
    {
        $storeEntity = $this->createStorePropelQuery()->findOneByName($storeName);

        $omsProductReservationEntity = (new SpyOmsProductReservation())
            ->setFkStore($storeEntity->getIdStore())
            ->setSku($sku)
            ->setReservationQuantity($quantity);

        $omsProductReservationEntity->save();

        return $omsProductReservationEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function createStockForStore(StoreTransfer $storeTransfer): StockTransfer
    {
        $stockTransfer = $this->haveStock();

        $this->haveStockStoreRelation(
            $stockTransfer,
            $storeTransfer
        );

        return $stockTransfer;
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    protected function createStorePropelQuery(): SpyStoreQuery
    {
        return SpyStoreQuery::create();
    }
}
