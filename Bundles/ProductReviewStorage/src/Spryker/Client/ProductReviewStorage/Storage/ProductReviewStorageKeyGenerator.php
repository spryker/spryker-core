<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductReviewStorage\Dependency\Service\ProductReviewStorageToSynchronizationServiceInterface;

class ProductReviewStorageKeyGenerator implements ProductReviewStorageKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\ProductReviewStorage\Dependency\Service\ProductReviewStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\ProductReviewStorage\Dependency\Service\ProductReviewStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(ProductReviewStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->synchronizationService = $synchronizationService;
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
            ->setReference($resourceId);

        return $this->synchronizationService->getStorageKeyBuilder($resourceName)->generateKey($synchronizationDataTransfer);
    }
}
