<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business\Storage;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainer;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface;

class PriceProductConcreteStorageWriter implements PriceProductConcreteStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @var string[] Keys are store ids, values are store names.
     */
    protected $storeNameMapBuffer;

    /**
     * @param \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(
        PriceProductStorageToPriceProductFacadeInterface $priceProductFacade,
        PriceProductStorageToStoreFacadeInterface $storeFacade,
        PriceProductStorageQueryContainerInterface $queryContainer,
        $isSendingToQueue
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->storeFacade = $storeFacade;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publish(array $productConcreteIds)
    {
        $productAbstractIdMap = $this->getProductAbstractIdMap($productConcreteIds);
        $priceGroups = $this->getProductConcretePriceGroup($productAbstractIdMap);

        $priceProductConcreteStorageEntities = $this->findPriceProductConcreteStorageEntities($productConcreteIds);
        $priceProductConcreteStorageMap = $this->getPriceProductConcreteStorageMap($priceProductConcreteStorageEntities);

        $this->storeData($priceGroups, $priceProductConcreteStorageMap);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function unpublish(array $productConcreteIds)
    {
        $priceProductConcreteStorageEntities = $this->findPriceProductConcreteStorageEntities($productConcreteIds);
        foreach ($priceProductConcreteStorageEntities as $priceProductConcreteStorageEntity) {
            $priceProductConcreteStorageEntity->delete();
        }
    }

    /**
     * @param array $priceGroups First level keys are product concrete ids, second level keys are store names, values are grouped prices.
     * @param array $priceProductConcreteStorageMap First level keys are product concrete ids, second level keys are store names, values are SpyPriceProductConcreteStorage objects
     *
     * @return void
     */
    protected function storeData(array $priceGroups, array $priceProductConcreteStorageMap)
    {
        foreach ($priceGroups as $idProductConcrete => $storePriceGroups) {
            foreach ($storePriceGroups as $storeName => $priceGroup) {
                $priceProductConcreteStorage = $this->getRelatedPriceProductConcreteStorageEntity(
                    $priceProductConcreteStorageMap,
                    $idProductConcrete,
                    $storeName
                );

                unset($priceProductConcreteStorageMap[$idProductConcrete][$storeName]);

                if ($this->hasProductConcretePrices($priceGroup)) {
                    $this->storePriceProduct(
                        $idProductConcrete,
                        $storeName,
                        $priceGroup,
                        $priceProductConcreteStorage
                    );

                    continue;
                }

                $this->deletePriceProduct($priceProductConcreteStorage);
            }
        }

        array_walk_recursive($priceProductConcreteStorageMap, function (SpyPriceProductConcreteStorage $priceProductConcreteStorageEntity) {
            $priceProductConcreteStorageEntity->delete();
        });
    }

    /**
     * @param array $priceProductConcreteStorageMap
     * @param int $idProductConcrete
     * @param string $storeName
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage
     */
    protected function getRelatedPriceProductConcreteStorageEntity($priceProductConcreteStorageMap, $idProductConcrete, $storeName)
    {
        if (isset($priceProductConcreteStorageMap[$idProductConcrete][$storeName])) {
            return $priceProductConcreteStorageMap[$idProductConcrete][$storeName];
        }

        return new SpyPriceProductConcreteStorage();
    }

    /**
     * @param array $priceGroup
     *
     * @return bool
     */
    protected function hasProductConcretePrices(array $priceGroup)
    {
        if ($priceGroup) {
            return true;
        }

        return false;
    }

    /**
     * @param int $idProductConcrete
     * @param string $storeName
     * @param array $priceGroup
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage $priceProductConcreteStorageEntity
     *
     * @return void
     */
    protected function storePriceProduct(
        $idProductConcrete,
        $storeName,
        array $priceGroup,
        SpyPriceProductConcreteStorage $priceProductConcreteStorageEntity
    ) {
        $priceProductStorageTransfer = (new PriceProductStorageTransfer())
            ->setPrices($priceGroup);

        $priceProductConcreteStorageEntity
            ->setFkProduct($idProductConcrete)
            ->setStore($storeName)
            ->setData($priceProductStorageTransfer->toArray(true))
            ->setIsSendingToQueue($this->isSendingToQueue)
            ->save();
    }

    /**
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage $priceProductConcreteStorageEntity
     *
     * @return void
     */
    protected function deletePriceProduct(SpyPriceProductConcreteStorage $priceProductConcreteStorageEntity)
    {
        if (!$priceProductConcreteStorageEntity->isNew()) {
            $priceProductConcreteStorageEntity->delete();
        }
    }

    /**
     * @param int[] $productAbstractIdMap Keys are product concrete ids, values are product abstract ids
     *
     * @return array First level keys are product concrete ids, second level keys are store names, values are grouped prices.
     */
    protected function getProductConcretePriceGroup(array $productAbstractIdMap)
    {
        $priceGroups = [];
        $priceProductCriteria = $this->getPriceCriteriaTransfer();
        foreach ($productAbstractIdMap as $idProductConcrete => $idProductAbstract) {
            $productConcretePriceProductTransfers = $this->priceProductFacade->findProductConcretePricesWithoutPriceExtraction($idProductConcrete, $idProductAbstract, $priceProductCriteria);
            $priceGroups[$idProductConcrete] = [];
            foreach ($productConcretePriceProductTransfers as $priceProductTransfer) {
                $storeName = $this->getStoreNameById($priceProductTransfer->getMoneyValue()->getFkStore());
                $priceGroups[$idProductConcrete][$storeName][] = $priceProductTransfer;
            }

            foreach ($priceGroups[$idProductConcrete] as $storeName => $priceProductTransferCollection) {
                $priceGroups[$idProductConcrete][$storeName] = $this->priceProductFacade->groupPriceProductCollection(
                    $priceProductTransferCollection
                );
            }
        }

        return $priceGroups;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    protected function getPriceCriteriaTransfer(): PriceProductCriteriaTransfer
    {
        return (new PriceProductCriteriaTransfer())
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType(PriceProductStorageConstants::PRICE_DIMENSION_DEFAULT)
            );
    }

    /**
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage[] $priceProductConcreteStorageEntities
     *
     * @return array
     */
    protected function getPriceProductConcreteStorageMap(array $priceProductConcreteStorageEntities)
    {
        $priceProductConcreteStorageMap = [];
        foreach ($priceProductConcreteStorageEntities as $storageEntity) {
            $priceProductConcreteStorageMap[$storageEntity->getFkProduct()][$storageEntity->getStore()] = $storageEntity;
        }

        return $priceProductConcreteStorageMap;
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return int[] Keys are product concrete ids, values are product abstract ids
     */
    protected function getProductAbstractIdMap(array $productConcreteIds)
    {
        return $this->queryContainer
            ->queryProductAbstractIdsByProductConcreteIds($productConcreteIds)
            ->find()
            ->toKeyValue(PriceProductStorageQueryContainer::ID_PRODUCT_CONCRETE, PriceProductStorageQueryContainer::ID_PRODUCT_ABSTRACT);
    }

    /**
     * @param int $idStore
     *
     * @return string
     */
    protected function getStoreNameById($idStore)
    {
        if (!$this->storeNameMapBuffer) {
            $this->loadStoreNameMap();
        }

        return $this->storeNameMapBuffer[$idStore];
    }

    /**
     * @return void
     */
    protected function loadStoreNameMap()
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        $this->storeNameMapBuffer = [];
        foreach ($storeTransfers as $storeTransfer) {
            $this->storeNameMapBuffer[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductConcreteStorage[]
     */
    protected function findPriceProductConcreteStorageEntities(array $productConcreteIds)
    {
        return $this->queryContainer
            ->queryPriceConcreteStorageByProductIds($productConcreteIds)
            ->find()
            ->getArrayCopy();
    }
}
