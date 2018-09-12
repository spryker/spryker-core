<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroupStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractGroupStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductGroupStorage\Dependency\Client\ProductGroupStorageToStorageClientInterface;
use Spryker\Client\ProductGroupStorage\Dependency\Service\ProductGroupStorageToSynchronizationServiceInterface;
use Spryker\Shared\ProductGroupStorage\ProductGroupStorageConstants;

class ProductGroupStorageReader implements ProductGroupStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductGroupStorage\Dependency\Client\ProductGroupStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductGroupStorage\Dependency\Service\ProductGroupStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductGroupStorage\Dependency\Client\ProductGroupStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductGroupStorage\Dependency\Service\ProductGroupStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(
        ProductGroupStorageToStorageClientInterface $storageClient,
        ProductGroupStorageToSynchronizationServiceInterface $synchronizationService
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractGroupStorageTransfer
     */
    public function findProductGroupItemsByIdProductAbstract($idProductAbstract)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference($idProductAbstract);

        $key = $this->synchronizationService
            ->getStorageKeyBuilder(ProductGroupStorageConstants::PRODUCT_GROUP_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);

        $productGroupData = $this->storageClient->get($key);
        $productAbstractGroupStorageTransfer = new ProductAbstractGroupStorageTransfer;

        if ($productGroupData) {
            $productAbstractGroupStorageTransfer->fromArray($productGroupData, true);
        }

        return $productAbstractGroupStorageTransfer;
    }
}
