<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceInterface;

class PriceProductStorageKeyGenerator implements PriceProductStorageKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(PriceProductStorageToSynchronizationServiceInterface $synchronizationService)
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
