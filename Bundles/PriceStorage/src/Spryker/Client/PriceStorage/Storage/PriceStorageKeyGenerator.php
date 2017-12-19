<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\PriceStorage\Dependency\Service\PriceStorageToSynchronizationServiceInterface;
use Spryker\Shared\Kernel\Store;

class PriceStorageKeyGenerator implements PriceStorageKeyGeneratorInterface
{
    /**
     * @var PriceStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @param PriceStorageToSynchronizationServiceInterface $synchronizationService
     * @param Store $store
     */
    public function __construct(PriceStorageToSynchronizationServiceInterface $synchronizationService, Store $store)
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
