<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetStorage\Storage;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductSetStorage\Dependency\Client\ProductSetStorageToStorageClientInterface;
use Spryker\Client\ProductSetStorage\Dependency\Service\ProductSetStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductSetStorage\Mapper\ProductSetStorageMapperInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductSetStorage\ProductSetStorageConstants;

class ProductSetStorageReader implements ProductSetStorageReaderInterface
{
    /**
     * @var ProductSetStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var ProductSetStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @var ProductSetStorageMapperInterface
     */
    protected $productSetStorageMapper;

    /**
     * @param ProductSetStorageToStorageClientInterface $storageClient
     * @param ProductSetStorageToSynchronizationServiceInterface $synchronizationService
     * @param Store $store
     */
    public function __construct(
        ProductSetStorageToStorageClientInterface $storageClient,
        ProductSetStorageToSynchronizationServiceInterface $synchronizationService,
        Store $store,
        ProductSetStorageMapperInterface $productSetStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
        $this->productSetStorageMapper = $productSetStorageMapper;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return ProductSetDataStorageTransfer
     */
    public function getProductSetByIdProductSet($idProductAbstract, $localeName)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference($idProductAbstract)
            ->setLocale($localeName)
            ->setStore($this->store->getStoreName());

        $key = $this->synchronizationService
            ->getStorageKeyBuilder(ProductSetStorageConstants::PRODUCT_SET_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);


        $productSet = $this->storageClient->get($key);

        if (!$productSet) {
            return null;
        }

        return $this->productSetStorageMapper->mapDataToTransfer($productSet);
    }
}
