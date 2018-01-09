<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductReviewStorage\Dependency\Service\ProductReviewStorageToSynchronizationServiceInterface;
use Spryker\Shared\Kernel\Store;

class ProductReviewStorageKeyGenerator implements ProductReviewStorageKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\ProductReviewStorage\Dependency\Service\ProductReviewStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Client\ProductReviewStorage\Dependency\Service\ProductReviewStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(ProductReviewStorageToSynchronizationServiceInterface $synchronizationService, Store $store)
    {
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
    }

    /**
     * @param string $resourceName
     * @param int $resourceId
     *
     * @return string
     */
    public function generateKey($resourceName, $resourceId)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setStore($this->store->getStoreName())
            ->setReference($resourceId);

        return $this->synchronizationService->getStorageKeyBuilder($resourceName)->generateKey($synchronizationDataTransfer);
    }
}
