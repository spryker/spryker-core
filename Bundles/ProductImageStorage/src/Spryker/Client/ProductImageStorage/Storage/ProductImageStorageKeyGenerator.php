<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductImageStorage\Dependency\Service\ProductImageStorageToSynchronizationServiceInterface;
use Spryker\Shared\Kernel\Store;

class ProductImageStorageKeyGenerator implements ProductImageStorageKeyGeneratorInterface
{
    /**
     * @var ProductImageStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @param ProductImageStorageToSynchronizationServiceInterface $synchronizationService
     * @param Store $store
     */
    public function __construct(ProductImageStorageToSynchronizationServiceInterface $synchronizationService, Store $store)
    {
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
    }

    /**
     * @param string $resourceName
     * @param int $resourceId
     * @param string $locale
     *
     * @return string
     */
    public function generateKey($resourceName, $resourceId, $locale)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setStore($this->store->getStoreName())
            ->setLocale($locale)
            ->setReference($resourceId);

        return $this->synchronizationService->getStorageKeyBuilder($resourceName)->generateKey($synchronizationDataTransfer);
    }
}
