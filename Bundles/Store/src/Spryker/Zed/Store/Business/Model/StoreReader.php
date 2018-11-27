<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface;
use Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException;
use Spryker\Zed\Store\Persistence\StoreQueryContainerInterface;

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
     * @var \Spryker\Zed\Store\Business\Model\StoreMapperInterface
     */
    protected $storeMapper;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected static $storeCache = [];

    /**
     * @param \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface $store
     * @param \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface $storeQueryContainer
     * @param \Spryker\Zed\Store\Business\Model\StoreMapperInterface $storeMapper
     */
    public function __construct(
        StoreToStoreInterface $store,
        StoreQueryContainerInterface $storeQueryContainer,
        StoreMapperInterface $storeMapper
    ) {
        $this->store = $store;
        $this->storeConfigurationProvider = $store;
        $this->storeQueryContainer = $storeQueryContainer;
        $this->storeMapper = $storeMapper;
    }

    /**
     * @return array
     */
    public function getAllStores()
    {
        $stores = $this->store->getAllStoreNames();
        $storeCollection = $this->storeQueryContainer
            ->queryStoresByNames($stores)
            ->find();

        $allStores = [];
        foreach ($storeCollection as $storeEntity) {
            $allStores[] = $this->storeMapper->mapEntityToTransfer($storeEntity);
        }

        return $allStores;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        $currentStore = $this->store->getCurrentStoreName();
        if (isset(static::$storeCache[$currentStore])) {
            return static::$storeCache[$currentStore];
        }

        $storeEntity = $this->storeQueryContainer
            ->queryStoreByName($currentStore)
            ->findOne();

        $storeTransfer = $this->storeMapper->mapEntityToTransfer($storeEntity);

        static::$storeCache[$currentStore] = $storeTransfer;

        return $storeTransfer;
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
        if (isset(static::$storeCache[$idStore])) {
            return static::$storeCache[$idStore];
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

        static::$storeCache[$idStore] = $storeTransfer;

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
        if (isset(static::$storeCache[$storeName])) {
            return static::$storeCache[$storeName];
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

        static::$storeCache[$storeName] = $storeTransfer;

        return $storeTransfer;
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
     * @return array
     */
    public function getCountries()
    {
        return $this->store->getCountries();
    }
}
