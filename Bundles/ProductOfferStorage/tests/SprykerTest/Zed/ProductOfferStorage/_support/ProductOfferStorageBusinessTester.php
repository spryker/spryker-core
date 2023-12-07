<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
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
 * @SuppressWarnings(\SprykerTest\Zed\ProductOfferStorage\PHPMD)
 */
class ProductOfferStorageBusinessTester extends Actor
{
    use _generated\ProductOfferStorageBusinessTesterActions;

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const PRODUCT_OFFER_STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @return void
     */
    public function clearProductOfferData(): void
    {
        $this->createProductOfferStoreQuery()->deleteAll();
        $this->createProductOfferStorageQuery()->deleteAll();
        $this->createProductConcreteProductOffersStorageQuery()->deleteAll();
    }

    /**
     * @param array<string> $productOfferReferences
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorage>
     */
    public function getProductOfferStorageEntities(array $productOfferReferences = []): ObjectCollection
    {
        return $this->createProductOfferStorageQuery()
            ->_if($productOfferReferences !== [])
                ->filterByProductOfferReference_In($productOfferReferences)
            ->_endIf()
            ->find();
    }

    /**
     * @param array<string> $productSkus
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage>
     */
    public function getProductConcreteProductOffersEntities(array $productSkus): ObjectCollection
    {
        return $this->createProductConcreteProductOffersStorageQuery()
            ->filterByConcreteSku_In($productSkus)
            ->orderByIdProductConcreteProductOffersStorage()
            ->find();
    }

    /**
     * @return void
     */
    public function clearProductOfferDataFromStorage(): void
    {
        $this->createProductConcreteProductOffersStorageQuery()->deleteAll();
        $this->createProductOfferStorageQuery()->deleteAll();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOffer(StoreTransfer $storeTransfer): ProductOfferTransfer
    {
        $productOfferData = [];
        $productOfferData[ProductOfferTransfer::CONCRETE_SKU] = $this->haveProduct()->getSku();
        $productOfferTransfer = $this->haveProductOffer($productOfferData)->addStore($storeTransfer);
        $this->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function createProductOfferWithConcreteSku(StoreTransfer $storeTransfer, string $sku): ProductOfferTransfer
    {
        $productOfferData = [];
        $productOfferData[ProductOfferTransfer::CONCRETE_SKU] = $sku;
        $productOfferTransfer = $this->haveProductOffer($productOfferData)->addStore($storeTransfer);
        $this->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function haveProductOfferStorage(ProductOfferStorageTransfer $productOfferStorageTransfer, StoreTransfer $storeTransfer): void
    {
        $productOfferStorageEntity = $this->createProductOfferStorageQuery()
            ->filterByProductOfferReference($productOfferStorageTransfer->getProductOfferReferenceOrFail())
            ->filterByStore($storeTransfer->getNameOrFail())
            ->findOneOrCreate();

        $productOfferStorageEntity->setData($productOfferStorageTransfer->toArray());
        $productOfferStorageEntity->save();
    }

    /**
     * @param string $concreteSku
     * @param string $storeName
     * @param array<mixed> $data
     *
     * @return void
     */
    public function haveProductConcreteProductOfferStorage(string $concreteSku, string $storeName, array $data): void
    {
        $productConcreteProductOffersStorageEntity = $this->createProductConcreteProductOffersStorageQuery()
            ->filterByConcreteSku($concreteSku)
            ->filterByStore($storeName)
            ->findOneOrCreate();

        $productConcreteProductOffersStorageEntity->setData($data);
        $productConcreteProductOffersStorageEntity->save();
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function haveStoreByName(string $storeName): StoreTransfer
    {
        return $this->haveStore([StoreTransfer::NAME => $storeName]);
    }

    /**
     * @param array<mixed> $productOfferData
     * @param array<mixed> $productData
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function haveProductOfferWithStore(array $productOfferData = [], array $productData = []): ProductOfferTransfer
    {
        $storeTransfer = $this->getLocator()->store()->facade()->getCurrentStore();
        $productConcreteTransfer = $this->haveProduct($productData);
        $productOfferData[ProductOfferTransfer::CONCRETE_SKU] = $productConcreteTransfer->getSkuOrFail();
        $productOfferTransfer = $this->haveProductOffer($productOfferData)->addStore($storeTransfer);
        $this->haveProductOfferStore($productOfferTransfer, $storeTransfer);

        return $productOfferTransfer;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSellableProductOfferData(): array
    {
        $productTransfer = $this->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);

        return [
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_STATUS_APPROVED,
            ProductOfferTransfer::CONCRETE_SKU => $productTransfer->getSkuOrFail(),
        ];
    }

    /**
     * @return array<string, list<mixed>
     */
    public static function getNotSellableProductOfferDataProvider(): array
    {
        return [
            'Product offer is not active' => [
                [
                    ProductOfferTransfer::IS_ACTIVE => false,
                    ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_STATUS_APPROVED,
                ],
                [
                    ProductConcreteTransfer::IS_ACTIVE => true,
                ],
            ],
            'Product offer is not approved' => [
                [
                    ProductOfferTransfer::IS_ACTIVE => true,
                    ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_STATUS_WAITING_FOR_APPROVAL,
                ],
                [
                    ProductConcreteTransfer::IS_ACTIVE => true,
                ],
            ],
            'Product is not active' => [
                [
                    ProductOfferTransfer::IS_ACTIVE => true,
                    ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_STATUS_APPROVED,
                ],
                [
                    ProductConcreteTransfer::IS_ACTIVE => false,
                ],
            ],
        ];
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorage> $productOfferStorageEntities
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return void
     */
    public function assertProductOfferStorageEntities(
        ObjectCollection $productOfferStorageEntities,
        ProductOfferTransfer $productOfferTransfer
    ): void {
        $this->assertCount(1, $productOfferStorageEntities);

        $data = $productOfferStorageEntities[0]->getData();

        $this->assertSame($productOfferTransfer->getStores()[0]->getNameOrFail(), $productOfferStorageEntities[0]->getStore());
        $this->assertSame($productOfferTransfer->getProductOfferReferenceOrFail(), $data['product_offer_reference']);
        $this->assertSame($productOfferTransfer->getConcreteSkuOrFail(), $data['product_concrete_sku']);
    }

    /**
     * @return \Orm\Zed\ProductOfferStorage\Persistence\SpyProductOfferStorageQuery
     */
    protected function createProductOfferStorageQuery(): SpyProductOfferStorageQuery
    {
        return SpyProductOfferStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery
     */
    protected function createProductConcreteProductOffersStorageQuery(): SpyProductConcreteProductOffersStorageQuery
    {
        return SpyProductConcreteProductOffersStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery
     */
    protected function createProductOfferStoreQuery(): SpyProductOfferStoreQuery
    {
        return SpyProductOfferStoreQuery::create();
    }
}
