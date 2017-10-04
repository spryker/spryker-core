<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model;

use Spryker\Zed\Store\Business\Model\Configuration\StoreConfigurationProviderInterface;
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

        $activeStores = [];
        foreach ($storeCollection as $storeEntity) {
            $activeStores[] = $this->storeMapper->mapEntityToTransfer($storeEntity);
        }

        return $activeStores;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        $currentStore = $this->storeConfigurationProvider->getStoreName();

        $storeEntity = $this->storeQueryContainer
            ->queryStoreByName($currentStore)
            ->findOne();

        return $this->storeMapper->mapEntityToTransfer($storeEntity);
    }

}
