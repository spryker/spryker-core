<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityResourceAliasStorage\Storage;

use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\AvailabilityResourceAliasStorage\Dependency\Client\AvailabilityResourceAliasStorageToStorageClientInterface;
use Spryker\Client\AvailabilityResourceAliasStorage\Dependency\Service\AvailabilityResourceAliasStorageToSynchronizationServiceInterface;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConstants;
use Spryker\Shared\Kernel\Store;

class AvailabilityStorageReader implements AvailabilityStorageReaderInterface
{
    protected const REFERENCE_PREFIX = 'sku:';
    /**
     * @var \Spryker\Client\AvailabilityResourceAliasStorage\Dependency\Client\AvailabilityResourceAliasStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\AvailabilityResourceAliasStorage\Dependency\Service\AvailabilityResourceAliasStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Client\AvailabilityResourceAliasStorage\Dependency\Client\AvailabilityResourceAliasStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\AvailabilityResourceAliasStorage\Dependency\Service\AvailabilityResourceAliasStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        AvailabilityResourceAliasStorageToStorageClientInterface $storageClient,
        AvailabilityResourceAliasStorageToSynchronizationServiceInterface $synchronizationService,
        Store $store
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    public function getAvailabilityAbstract(string $sku): SpyAvailabilityAbstractEntityTransfer
    {
        $key = $this->generateKey($sku);
        $mappingResource = $this->storageClient->get($key);

        $spyAvailabilityAbstractEntityTransfer = new SpyAvailabilityAbstractEntityTransfer();
        if ($mappingResource === null) {
            return $spyAvailabilityAbstractEntityTransfer;
        }
        $availability = $this->storageClient->get($mappingResource['key']);
        if ($availability === null) {
            return $spyAvailabilityAbstractEntityTransfer;
        }

        return $spyAvailabilityAbstractEntityTransfer->fromArray($availability, true);
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    protected function generateKey(string $sku): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($this->store->getStoreName());
        $synchronizationDataTransfer->setReference(static::REFERENCE_PREFIX . $sku);

        return $this->synchronizationService
            ->getStorageKeyBuilder(AvailabilityStorageConstants::AVAILABILITY_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
