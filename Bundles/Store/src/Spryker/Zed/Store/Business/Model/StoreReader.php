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
        if ($this->storeCache->hasStoreTransferByStoreId($idStore)) {
            return $this->storeCache->getStoreTransferByStoreId($idStore);
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

        $this->storeCache->cacheStoreTransfer($storeTransfer);

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
        if ($this->storeCache->hasStoreTransferByStoreName($storeName)) {
            return $this->storeCache->getStoreTransferByStoreName($storeName);
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

        $this->storeCache->cacheStoreTransfer($storeTransfer);

        return $storeTransfer;
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function findStoreByName(string $storeName): ?StoreTransfer
    {
        if ($this->storeCache->hasStoreTransferByStoreName($storeName)) {
            return $this->storeCache->getStoreTransferByStoreName($storeName);
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
        $unresolvedStoreNames = [];
        $storeTransfersFromCache = [];

        foreach ($storeNames as $storeName) {
            if (!$this->storeCache->hasStoreTransferByStoreName($storeName)) {
                $unresolvedStoreNames[] = $storeName;

                continue;
            }

            $storeTransfersFromCache[] = $this->storeCache->getStoreTransferByStoreName($storeName);
        }

        if ($unresolvedStoreNames) {
            $storeTransfers = $this->storeRepository->getStoreTransfersByStoreNames($storeNames);

            foreach ($storeTransfers as $storeTransfer) {
                $this->storeCache->cacheStoreTransfer($storeTransfer);
                $storeTransfersFromCache[] = $storeTransfer;
            }
        }

        return $storeTransfersFromCache;
    }
}
