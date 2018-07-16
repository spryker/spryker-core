<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductSetStorage\Dependency\Client\ProductSetStorageToStorageClientInterface;
use Spryker\Client\ProductSetStorage\Dependency\Service\ProductSetStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductSetStorage\Mapper\ProductSetStorageMapperInterface;
use Spryker\Shared\ProductSetStorage\ProductSetStorageConstants;

class ProductSetStorageReader implements ProductSetStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductSetStorage\Dependency\Client\ProductSetStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductSetStorage\Dependency\Service\ProductSetStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductSetStorage\Mapper\ProductSetStorageMapperInterface
     */
    protected $productSetStorageMapper;

    /**
     * @param \Spryker\Client\ProductSetStorage\Dependency\Client\ProductSetStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductSetStorage\Dependency\Service\ProductSetStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductSetStorage\Mapper\ProductSetStorageMapperInterface $productSetStorageMapper
     */
    public function __construct(
        ProductSetStorageToStorageClientInterface $storageClient,
        ProductSetStorageToSynchronizationServiceInterface $synchronizationService,
        ProductSetStorageMapperInterface $productSetStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->productSetStorageMapper = $productSetStorageMapper;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    public function getProductSetByIdProductSet($idProductAbstract, $localeName)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference($idProductAbstract)
            ->setLocale($localeName);

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
