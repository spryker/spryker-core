<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface;
use Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductStorage\ProductStorageConstants;

class ProductConcreteStorageReader implements ProductConcreteStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductStorage\Dependency\Service\ProductStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductStorageToStorageClientInterface $storageClient,
        ProductStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idProductConcrete
     * @param string $localeName
     *
     * @return array
     */
    public function getProductConcreteStorageData($idProductConcrete, $localeName)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setLocale($localeName)
            ->setReference($idProductConcrete);

        $key = $this->synchronizationService
            ->getStorageKeyBuilder(ProductStorageConstants::PRODUCT_CONCRETE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);

        return $this->storageClient->get($key);
    }
}
