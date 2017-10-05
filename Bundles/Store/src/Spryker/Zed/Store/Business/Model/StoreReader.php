<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model;

use Spryker\Zed\Store\Business\Model\Configuration\StoreConfigurationProviderInterface;
use Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException;
use Spryker\Zed\Store\Persistence\StoreQueryContainerInterface;

class StoreReader implements StoreReaderInterface
{

    /**
     * @var \Spryker\Zed\Store\Business\Model\Configuration\StoreConfigurationProviderInterface
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
     * @param \Spryker\Zed\Store\Business\Model\Configuration\StoreConfigurationProviderInterface $storeConfigurationProvider
     * @param \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface $storeQueryContainer
     * @param \Spryker\Zed\Store\Business\Model\StoreMapperInterface $storeMapper
     */
    public function __construct(
        StoreConfigurationProviderInterface $storeConfigurationProvider,
        StoreQueryContainerInterface $storeQueryContainer,
        StoreMapperInterface $storeMapper
    ) {
        $this->storeConfigurationProvider = $storeConfigurationProvider;
        $this->storeQueryContainer = $storeQueryContainer;
        $this->storeMapper = $storeMapper;
    }

    /**
     * @return array
     */
    public function getAllStores()
    {
        $stores = $this->storeConfigurationProvider->getAllStoreNames();
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
        $currentStore = $this->storeConfigurationProvider->getCurrentStoreName();

        $storeEntity = $this->storeQueryContainer
            ->queryStoreByName($currentStore)
            ->findOne();

        return $this->storeMapper->mapEntityToTransfer($storeEntity);
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
         $storeEntity = $this->storeQueryContainer
             ->queryStoreById($idStore)
             ->findOne();

        if (!$storeEntity) {
            throw new StoreNotFoundException(
                sprintf('Store with id "%s" not found!', $idStore)
            );
        }

         return $this->storeMapper->mapEntityToTransfer($storeEntity);
    }

}
