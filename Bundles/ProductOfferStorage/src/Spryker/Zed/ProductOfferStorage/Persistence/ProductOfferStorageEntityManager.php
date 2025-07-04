<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStorage\Persistence;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

/**
 * @method \Spryker\Zed\ProductOfferStorage\Persistence\ProductOfferStoragePersistenceFactory getFactory()
 */
class ProductOfferStorageEntityManager extends AbstractEntityManager implements ProductOfferStorageEntityManagerInterface
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @param string $concreteSku
     * @param array<string, mixed> $data
     * @param string $storeName
     *
     * @return void
     */
    public function saveProductConcreteProductOffers(string $concreteSku, array $data, string $storeName): void
    {
        $productConcreteProductOffersStorageEntity = $this->getFactory()
            ->createProductConcreteProductOffersStoragePropelQuery()
            ->filterByConcreteSku($concreteSku)
            ->filterByStore($storeName)
            ->findOneOrCreate();

        $productConcreteProductOffersStorageEntity->setData($data);
        $productConcreteProductOffersStorageEntity->save();
    }

    /**
     * @param array<string, array<string, array<mixed>>> $productOfferToSaveCollection
     *
     * @return void
     */
    public function saveProductConcreteProductOffersStorageBatch(array $productOfferToSaveCollection): void
    {
        if (!$productOfferToSaveCollection) {
            return;
        }

        $existedProductOffers = $this->loadExistedProductOffers($productOfferToSaveCollection);
        foreach ($productOfferToSaveCollection as $concreteSku => $productOfferDataList) {
            foreach ($productOfferDataList as $storeName => $productOfferData) {
                $productConcreteProductOffersStorageEntity = $existedProductOffers[$concreteSku][$storeName] ?? new SpyProductConcreteProductOffersStorage();
                $productConcreteProductOffersStorageEntity->setConcreteSku($concreteSku);
                $productConcreteProductOffersStorageEntity->setStore($storeName);
                $productConcreteProductOffersStorageEntity->setData($productOfferData);
                $this->persist($productConcreteProductOffersStorageEntity);
            }
        }

        $this->commit();
    }

    /**
     * @param array<string, array<string, array<mixed>>> $productOfferToSaveCollection
     *
     * @return array
     */
    protected function loadExistedProductOffers(array $productOfferToSaveCollection): array
    {
        $concreteSkuList = [];
        foreach ($productOfferToSaveCollection as $concreteSku => $productOfferData) {
            $concreteSkuList[$concreteSku] = $concreteSku;
        }

        $productConcreteProductOffersStorageEntityCollection = $this->getFactory()
            ->createProductConcreteProductOffersStoragePropelQuery()
            ->filterByConcreteSku_In($concreteSkuList)
            ->find();

        return $this->getProductOfferStorageEntitiesIndexedBySkuAndStore($productConcreteProductOffersStorageEntityCollection);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $productConcreteProductOffersStorageEntityCollection
     *
     * @return array
     */
    protected function getProductOfferStorageEntitiesIndexedBySkuAndStore(Collection $productConcreteProductOffersStorageEntityCollection): array
    {
        $result = [];
        /** @var \Orm\Zed\ProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorage $productConcreteProductOffersStorageEntity */
        foreach ($productConcreteProductOffersStorageEntityCollection as $productConcreteProductOffersStorageEntity) {
            $result[$productConcreteProductOffersStorageEntity->getConcreteSku()][$productConcreteProductOffersStorageEntity->getStore()] = $productConcreteProductOffersStorageEntity;
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return void
     */
    public function saveProductOfferStorage(ProductOfferTransfer $productOfferTransfer): void
    {
        foreach ($productOfferTransfer->getStores() as $storeTransfer) {
            $productOfferStorageEntity = $this->getFactory()
                ->createProductOfferStoragePropelQuery()
                ->filterByStore($storeTransfer->getName())
                ->filterByProductOfferReference($productOfferTransfer->getProductOfferReference())
                ->findOneOrCreate();

            $productOfferStorageTransfer = $this->getFactory()
                ->createProductOfferStorageMapper()
                ->mapProductOfferTransferToProductOfferStorageTransfer($productOfferTransfer, (new ProductOfferStorageTransfer()));

            $productOfferStorageEntity->setData($productOfferStorageTransfer->toArray());
            $productOfferStorageEntity->save();
        }
    }

    /**
     * @param array<string> $productSkus
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductConcreteProductOffersStorageEntitiesByProductSkus(
        array $productSkus,
        ?string $storeName = null
    ): void {
        $query = $this->getFactory()
            ->createProductConcreteProductOffersStoragePropelQuery()
            ->filterByConcreteSku_In($productSkus);

        if ($storeName) {
            $query->filterByStore($storeName);
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $productConcreteProductOffersStorageCollection */
        $productConcreteProductOffersStorageCollection = $query->find();
        $productConcreteProductOffersStorageCollection->delete();
    }

    /**
     * @param array<string> $productOfferReferences
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferStorageEntitiesByProductOfferReferences(array $productOfferReferences, ?string $storeName = null): void
    {
        $query = $this->getFactory()
            ->createProductOfferStoragePropelQuery()
            ->filterByProductOfferReference_In($productOfferReferences);

        if ($storeName) {
            $query->filterByStore($storeName);
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $productOfferStorageCollection */
        $productOfferStorageCollection = $query->find();
        $productOfferStorageCollection->delete();
    }
}
