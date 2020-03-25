<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface;
use Spryker\Zed\Store\Business\Cache\StoreCacheInterface;
use Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException;
use Spryker\Zed\Store\Persistence\StoreQueryContainerInterface;
use Spryker\Zed\Store\Persistence\StoreRepositoryInterface;

class StoreReader implements StoreReaderInterface
{
    /**
     * @var \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    protected $store;

    /**
     * @deprecated Use StoreReader::store instead.
     *
     * @var \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    protected $storeConfigurationProvider;

    /**
     * @var \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface
     */
    protected $storeQueryContainer;

    /**
     * @var \Spryker\Zed\Store\Persistence\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var \Spryker\Zed\Store\Business\Model\StoreMapperInterface
     */
    protected $storeMapper;

    /**
     * @var \Spryker\Zed\Store\Business\Cache\StoreCacheInterface
     */
    protected $storeCache;

    /**
     * @param \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface $store
     * @param \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface $storeQueryContainer
     * @param \Spryker\Zed\Store\Persistence\StoreRepositoryInterface $storeRepository
     * @param \Spryker\Zed\Store\Business\Model\StoreMapperInterface $storeMapper
     * @param \Spryker\Zed\Store\Business\Cache\StoreCacheInterface $storeCache
     */
    public function __construct(
        StoreToStoreInterface $store,
        StoreQueryContainerInterface $storeQueryContainer,
        StoreRepositoryInterface $storeRepository,
        StoreMapperInterface $storeMapper,
        StoreCacheInterface $storeCache
    ) {
        $this->store = $store;
        $this->storeConfigurationProvider = $store;
        $this->storeQueryContainer = $storeQueryContainer;
        $this->storeRepository = $storeRepository;
        $this->storeMapper = $storeMapper;
        $this->storeCache = $storeCache;
    }

    /**
     * @return array
     */
    public function getAllStores()
    {
        $stores = $this->store->getAllStoreNames();

        return $this->getStoreTransfersByStoreNames($stores);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        $currentStore = $this->store->getCurrentStoreName();

        return $this->getStoreByName($currentStore);
    }

    /**
     * @param int $idStore
     *
     * @throws \Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreById($idStore)
    {
        if ($this->storeCache->hasStoreByStoreId($idStore)) {
            return $this->storeCache->getStoreByStoreId($idStore);
        }

         $storeEntity = $this->storeQueryContainer
             ->queryStoreById($idStore)
             ->findOne();

        if (!$storeEntity) {
            throw new StoreNotFoundException(
                sprintf('Store with id "%s" not found!', $idStore)
            );
        }

        $storeTransfer = $this->storeMapper->mapEntityToTransfer($storeEntity);

        $this->storeCache->cacheStore($storeTransfer);

        return $storeTransfer;
    }

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName($storeName)
    {
        if ($this->storeCache->hasStoreByStoreName($storeName)) {
            return $this->storeCache->getStoreByStoreName($storeName);
        }

        $storeEntity = $this->storeQueryContainer
            ->queryStoreByName($storeName)
            ->findOne();

        if (!$storeEntity) {
            throw new StoreNotFoundException(
                sprintf('Store with name "%s" not found!', $storeName)
            );
        }

        $storeTransfer = $this->storeMapper->mapEntityToTransfer($storeEntity);

        $this->storeCache->cacheStore($storeTransfer);

        return $storeTransfer;
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function findStoreByName(string $storeName): ?StoreTransfer
    {
        if ($this->storeCache->hasStoreByStoreName($storeName)) {
            return $this->storeCache->getStoreByStoreName($storeName);
        }

        if (!$this->storeRepository->storeExists($storeName)) {
            return null;
        }

        return $this->getStoreByName($storeName);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresWithSharedPersistence(StoreTransfer $storeTransfer)
    {
        $stores = [];
        foreach ($storeTransfer->getStoresWithSharedPersistence() as $storeName) {
            $stores[] = $this->getStoreByName($storeName);
        }

        return $stores;
    }

    /**
     * @return string[]
     */
    public function getCountries()
    {
        return $this->store->getCountries();
    }

    /**
     * @param string[] $storeNames
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoreTransfersByStoreNames(array $storeNames): array
    {
        $storeNames = array_unique($storeNames);
        $unresolvedStoreNames = $this->getNotCachesStoreNames($storeNames);
        $resolvedStoreTransfers = $this->getStoreTransfersByStoreNames(array_diff($storeNames, $unresolvedStoreNames));

        if ($unresolvedStoreNames) {
            $storeTransfers = $this->storeRepository->getStoreTransfersByStoreNames($storeNames);
            $this->cacheStoreTransfers($storeTransfers);
            $resolvedStoreTransfers = array_merge($resolvedStoreTransfers, $storeTransfers);
        }

        return $resolvedStoreTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return void
     */
    protected function cacheStoreTransfers(array $storeTransfers): void
    {
        foreach ($storeTransfers as $storeTransfer) {
            $this->storeCache->cacheStore($storeTransfer);
        }
    }

    /**
     * @param string[] $storeNames
     *
     * @return string[]
     */
    protected function getNotCachesStoreNames(array $storeNames): array
    {
        $unresolvedStoreNames = [];

        foreach ($storeNames as $storeName) {
            if ($this->storeCache->hasStoreByStoreName($storeName)) {
                continue;
            }

            $unresolvedStoreNames[] = $storeName;
        }

        return $unresolvedStoreNames;
    }

    /**
     * @param string[] $storeNames
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function getStoresByStoreNamesFromCache(array $storeNames): array
    {
        $resolvedStoreTransfers = [];

        foreach ($storeNames as $storeName) {
            if (!$this->storeCache->hasStoreByStoreName($storeName)) {
                continue;
            }

            $resolvedStoreTransfers[] = $this->storeCache->getStoreByStoreName($storeName);
        }

        return $resolvedStoreTransfers;
    }
}
