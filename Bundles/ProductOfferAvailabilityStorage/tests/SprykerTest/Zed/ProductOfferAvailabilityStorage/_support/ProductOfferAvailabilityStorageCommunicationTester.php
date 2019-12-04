<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailabilityStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservation;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage;
use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorageQuery;
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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferAvailabilityStorageCommunicationTester extends Actor
{
    use _generated\ProductOfferAvailabilityStorageCommunicationTesterActions;

    protected const TEST_SKU = 'test-sku';

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
            ProductOfferTransfer::CONCRETE_SKU => static::TEST_SKU,
        ]);

        $productOfferStockEntity = (new SpyProductOfferStock())
            ->setQuantity($quantity)
            ->setFkProductOffer($productOfferTransfer->getIdProductOffer())
            ->setFkStock($stockTransfer->getIdStock());

        $productOfferStockEntity->save();

        return $productOfferStockEntity;
    }

    /**
     * @param string $quantity
     * @param string $storeName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservation
     */
    public function createOmsProductReservation(string $quantity, string $storeName): SpyOmsProductReservation
    {
        $storeEntity = $this->createStorePropelQuery()->findOneByName($storeName);

        $omsProductReservationEntity = (new SpyOmsProductReservation())
            ->setFkStore($storeEntity->getIdStore())
            ->setSku(static::TEST_SKU)
            ->setReservationQuantity($quantity);

        $omsProductReservationEntity->save();

        return $omsProductReservationEntity;
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
     * @return void
     */
    public function truncateProductOfferAvailabilityStorage(): void
    {
        $this->truncateTableRelations($this->getProductOfferAvailabilityStoragePropelQuery());
    }

    /**
     * @return void
     */
    public function truncateOmsProductReservations(): void
    {
        $this->truncateTableRelations($this->getOmsProductReservationPropelQuery());
    }

    /**
     * @param string $storeName
     * @param string $productOfferReference
     *
     * @return \Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage|null
     */
    public function findProductOfferAvailabilityStorage(string $storeName, string $productOfferReference): ?SpyProductOfferAvailabilityStorage
    {
        return $this->getProductOfferAvailabilityStoragePropelQuery()
            ->filterByStore($storeName)
            ->findOneByProductOfferReference($productOfferReference);
    }

    /**
     * @return \Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorageQuery
     */
    protected function getProductOfferAvailabilityStoragePropelQuery(): SpyProductOfferAvailabilityStorageQuery
    {
        return SpyProductOfferAvailabilityStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    protected function getOmsProductReservationPropelQuery(): SpyOmsProductReservationQuery
    {
        return SpyOmsProductReservationQuery::create();
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    protected function createStorePropelQuery(): SpyStoreQuery
    {
        return SpyStoreQuery::create();
    }
}
