<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
use Propel\Runtime\Collection\ObjectCollection;

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
class MerchantProductOfferStorageTester extends Actor
{
    use _generated\MerchantProductOfferStorageTesterActions;

    /**
     * @param string $productOfferReference
     *
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getProductOfferEntities(string $productOfferReference): ObjectCollection
    {
        return SpyProductOfferStorageQuery::create()->findByProductOfferReference($productOfferReference);
    }

    /**
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findAllProductOfferEntities(): ObjectCollection
    {
        return SpyProductOfferStorageQuery::create()->find();
    }

    /**
     * @param string $productSku
     *
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getProductConcreteProductOffersEntities(string $productSku): ObjectCollection
    {
        return SpyProductConcreteProductOffersStorageQuery::create()->findByConcreteSku($productSku);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getAllStoreTransfers(): array
    {
        $deStoreTransfer = (new StoreTransfer())->setName('DE')->setIdStore(1);
        $atStoreTransfer = (new StoreTransfer())->setName('AT')->setIdStore(2);
        $usStoreTransfer = (new StoreTransfer())->setName('US')->setIdStore(3);

        return [$deStoreTransfer, $atStoreTransfer, $usStoreTransfer];
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array $productOfferData
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOffer(StoreTransfer $storeTransfer, array $productOfferData = [], array $productData = []): ProductOfferTransfer
    {
        $productOfferData[ProductOfferTransfer::FK_MERCHANT] = $this->haveMerchant([MerchantTransfer::IS_ACTIVE => true])->getIdMerchant();
        $productOfferData[ProductOfferTransfer::CONCRETE_SKU] = $this->haveProduct($productData)->getSku();

        $productOfferTransfer = $this->haveProductOffer($productOfferData)->addStore($storeTransfer);

        $this->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        return $productOfferTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionTransfer(): ProductOfferCollectionTransfer
    {
        $storeTransfer = $this->getAllStoreTransfers();
        $storeTransfer = array_shift($storeTransfer);

        $productOfferCollectionTransfer = new ProductOfferCollectionTransfer();
        $productOfferCollectionTransfer->addProductOffer($this->createProductOffer($storeTransfer));

        return $productOfferCollectionTransfer;
    }
}
