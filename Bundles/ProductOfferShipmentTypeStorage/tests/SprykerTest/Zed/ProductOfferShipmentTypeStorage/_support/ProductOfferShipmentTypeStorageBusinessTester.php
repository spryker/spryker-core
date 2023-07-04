<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShipmentTypeStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferShipmentTypeStorageTransfer;
use Orm\Zed\ProductOfferShipmentTypeStorage\Persistence\SpyProductOfferShipmentTypeStorageQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\ProductOfferShipmentTypeStorageFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductOfferShipmentTypeStorage\PHPMD)
 */
class ProductOfferShipmentTypeStorageBusinessTester extends Actor
{
    use _generated\ProductOfferShipmentTypeStorageBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureProductOfferShipmentTypeStorageTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getProductOfferShipmentTypeStorageQuery());
    }

    /**
     * @return void
     */
    public function ensureStoreTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getStoreQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeStorageTransfer $productOfferShipmentTypeStorageTransfer
     * @param string $storeName
     *
     * @return void
     */
    public function createProductOfferShipmentTypeStorage(
        ProductOfferShipmentTypeStorageTransfer $productOfferShipmentTypeStorageTransfer,
        string $storeName
    ): void {
        $productOfferShipmentTypeStorageEntity = $this->getProductOfferShipmentTypeStorageQuery()
            ->filterByProductOfferReference($productOfferShipmentTypeStorageTransfer->getProductOfferReference())
            ->filterByStore($storeName)
        ->findOneOrCreate();

        $productOfferShipmentTypeStorageEntity->setData($productOfferShipmentTypeStorageTransfer->toArray());
        $productOfferShipmentTypeStorageEntity->save();
    }

    /**
     * @param string $productOfferReference
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeStorageTransfer|null
     */
    public function findProductOfferShipmentTypeStorageTransfer(string $productOfferReference, string $storeName): ?ProductOfferShipmentTypeStorageTransfer
    {
        $productOfferShipmentTypeStorageEntity = $this->getProductOfferShipmentTypeStorageQuery()
            ->filterByProductOfferReference($productOfferReference)
            ->filterByStore($storeName)
            ->findOne();

        if (!$productOfferShipmentTypeStorageEntity) {
            return null;
        }

        return (new ProductOfferShipmentTypeStorageTransfer())->fromArray($productOfferShipmentTypeStorageEntity->getData(), true);
    }

    /**
     * @return \Orm\Zed\ProductOfferShipmentTypeStorage\Persistence\SpyProductOfferShipmentTypeStorageQuery
     */
    protected function getProductOfferShipmentTypeStorageQuery(): SpyProductOfferShipmentTypeStorageQuery
    {
        return SpyProductOfferShipmentTypeStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    protected function getStoreQuery(): SpyStoreQuery
    {
        return SpyStoreQuery::create();
    }
}
