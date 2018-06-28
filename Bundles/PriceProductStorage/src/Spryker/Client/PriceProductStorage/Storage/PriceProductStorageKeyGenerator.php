<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStoreClientInterface;
use Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceInterface;

class PriceProductStorageKeyGenerator implements PriceProductStorageKeyGeneratorInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        PriceProductStorageToSynchronizationServiceInterface $synchronizationService,
        PriceProductStorageToStoreClientInterface $storeClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storeClient = $storeClient;
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
            ->setReference($resourceId)
            ->setStore($this->storeClient->getCurrentStore()->getName());

        return $this->synchronizationService->getStorageKeyBuilder($resourceName)->generateKey($synchronizationDataTransfer);
    }
}
