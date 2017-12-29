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
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductGroupStorage\ProductGroupStorageConstants;

class ProductGroupStorageReader implements ProductGroupStorageReaderInterface
{
    /**
     * @var ProductGroupStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var ProductGroupStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @param ProductGroupStorageToStorageClientInterface $storageClient
     * @param ProductGroupStorageToSynchronizationServiceInterface $synchronizationService
     * @param Store $store
     */
    public function __construct(
        ProductGroupStorageToStorageClientInterface $storageClient,
        ProductGroupStorageToSynchronizationServiceInterface $synchronizationService,
        Store $store
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return ProductAbstractGroupStorageTransfer
     */
    public function findProductGroupItemsByIdProductAbstract($idProductAbstract, $localeName)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference($idProductAbstract)
            ->setLocale($localeName)
            ->setStore($this->store->getStoreName());

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
