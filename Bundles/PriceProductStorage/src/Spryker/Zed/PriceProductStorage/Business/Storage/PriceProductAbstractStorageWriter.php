<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business\Storage;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface;
use Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface;

class PriceProductAbstractStorageWriter implements PriceProductAbstractStorageWriterInterface
{
    use LoggerTrait;

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
     * @var array<string> Keys are store ids, values are store names.
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
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $priceGroups = $this->getProductAbstractPriceGroups($productAbstractIds);

        $priceProductAbstractStorageEntities = $this->findPriceProductAbstractStorageEntities($productAbstractIds);
        $priceProductAbstractStorageMap = $this->getPriceProductAbstractStorageMap($priceProductAbstractStorageEntities);

        $this->storeData($priceGroups, $priceProductAbstractStorageMap);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $priceProductAbstractStorageEntities = $this->findPriceProductAbstractStorageEntities($productAbstractIds);
        foreach ($priceProductAbstractStorageEntities as $priceProductAbstractStorageEntity) {
            $priceProductAbstractStorageEntity->delete();
        }
    }

    /**
     * @param array<int, array<string, array<mixed>>> $priceGroups First level keys are product abstract ids, second level keys are store names, values are grouped prices.
     * @param array<int, array<string, \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage>> $priceProductAbstractStorageMap First level keys are product abstract ids, second level keys are store names.
     *
     * @return void
     */
    protected function storeData(array $priceGroups, array $priceProductAbstractStorageMap)
    {
        foreach ($priceGroups as $idProductAbstract => $storePriceGroups) {
            foreach ($storePriceGroups as $storeName => $priceGroup) {
                $priceProductAbstractStorage = $this->getRelatedPriceProductAbstractStorageEntity(
                    $priceProductAbstractStorageMap,
                    $idProductAbstract,
                    $storeName,
                );

                unset($priceProductAbstractStorageMap[$idProductAbstract][$storeName]);

                if ($this->hasProductAbstractPrices($priceGroup)) {
                    $this->storePriceProduct(
                        $idProductAbstract,
                        $storeName,
                        $priceGroup,
                        $priceProductAbstractStorage,
                    );

                    continue;
                }

                $this->deletePriceProduct($priceProductAbstractStorage);
            }
        }

        array_walk_recursive($priceProductAbstractStorageMap, function (SpyPriceProductAbstractStorage $priceProductAbstractStorageEntity) {
            $priceProductAbstractStorageEntity->delete();
        });
    }

    /**
     * @param array<int, array<string, \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage>> $priceProductAbstractStorageMap
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage
     */
    protected function getRelatedPriceProductAbstractStorageEntity($priceProductAbstractStorageMap, $idProductAbstract, $storeName)
    {
        if (isset($priceProductAbstractStorageMap[$idProductAbstract][$storeName])) {
            return $priceProductAbstractStorageMap[$idProductAbstract][$storeName];
        }

        return new SpyPriceProductAbstractStorage();
    }

    /**
     * @param array<mixed> $priceGroup
     *
     * @return bool
     */
    protected function hasProductAbstractPrices(array $priceGroup)
    {
        if ($priceGroup) {
            return true;
        }

        return false;
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     * @param array<mixed> $priceGroup
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage $priceProductAbstractStorageEntity
     *
     * @return void
     */
    protected function storePriceProduct(
        $idProductAbstract,
        $storeName,
        array $priceGroup,
        SpyPriceProductAbstractStorage $priceProductAbstractStorageEntity
    ) {
        $priceProductStorageTransfer = (new PriceProductStorageTransfer())
            ->setPrices($priceGroup);

        $priceProductAbstractStorageEntity
            ->setFkProductAbstract($idProductAbstract)
            ->setStore($storeName)
            ->setData($priceProductStorageTransfer->toArray())
            ->setIsSendingToQueue($this->isSendingToQueue);

        $priceProductAbstractStorageEntity->save();
    }

    /**
     * @param \Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage $priceProductAbstractStorageEntity
     *
     * @return void
     */
    protected function deletePriceProduct(SpyPriceProductAbstractStorage $priceProductAbstractStorageEntity)
    {
        if (!$priceProductAbstractStorageEntity->isNew()) {
            $priceProductAbstractStorageEntity->delete();
        }
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage>
     */
    protected function findPriceProductAbstractStorageEntities(array $productAbstractIds)
    {
        return $this->queryContainer
            ->queryPriceAbstractStorageByPriceAbstractIds($productAbstractIds)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    protected function getProductAbstractPriceGroups(array $productAbstractIds)
    {
        $priceGroups = [];
        $priceGroupsCollection = [];
        $priceProductCriteriaTransfer = $this->getPriceCriteriaTransfer();
        $productAbstractPriceProductTransfers = $this->priceProductFacade->findProductAbstractPricesWithoutPriceExtractionByProductAbstractIdsAndCriteria($productAbstractIds, $priceProductCriteriaTransfer);

        foreach ($productAbstractPriceProductTransfers as $key => $priceProductTransfer) {
            $idProductAbstract = $priceProductTransfer->getIdProductAbstract();
            $storeName = $this->getStoreNameById($priceProductTransfer->getMoneyValue()->getFkStore());
            $priceGroups[$idProductAbstract][$storeName][] = $priceProductTransfer;
        }

        foreach ($productAbstractIds as $idProductAbstract) {
            if (!isset($priceGroups[$idProductAbstract])) {
                $this->getLogger()->warning(sprintf('Product abstract `%s` has no default price', $idProductAbstract));

                continue;
            }

            $priceGroupsCollection[$idProductAbstract] = $this->getProductAbstractPriceStoreGroups($priceGroups[$idProductAbstract], $idProductAbstract);
        }

        return $priceGroupsCollection;
    }

    /**
     * @param array $productAbstractPriceGroups
     * @param int $idProductAbstract
     *
     * @return array
     */
    protected function getProductAbstractPriceStoreGroups(array $productAbstractPriceGroups, int $idProductAbstract): array
    {
        $priceGroupsCollection = [];
        foreach ($productAbstractPriceGroups as $storeName => $priceProductTransferCollection) {
            $priceGroupsCollection[$storeName] = $this->priceProductFacade->groupPriceProductCollection(
                $priceProductTransferCollection,
            );
        }

        return $priceGroupsCollection;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    protected function getPriceCriteriaTransfer(): PriceProductCriteriaTransfer
    {
        return (new PriceProductCriteriaTransfer())
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType(PriceProductStorageConstants::PRICE_DIMENSION_DEFAULT),
            );
    }

    /**
     * @param array<\Orm\Zed\PriceProductStorage\Persistence\SpyPriceProductAbstractStorage> $priceProductAbstractStorageEntities
     *
     * @return array
     */
    protected function getPriceProductAbstractStorageMap(array $priceProductAbstractStorageEntities)
    {
        $priceProductAbstractStorageMap = [];
        foreach ($priceProductAbstractStorageEntities as $storageEntity) {
            $priceProductAbstractStorageMap[$storageEntity->getFkProductAbstract()][$storageEntity->getStore()] = $storageEntity;
        }

        return $priceProductAbstractStorageMap;
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
}
