<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductResourceAliasStorage\Storage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\PriceProductResourceAliasStorage\Dependency\Client\PriceProductResourceAliasStorageToStorageClientInterface;
use Spryker\Client\PriceProductResourceAliasStorage\Dependency\Service\PriceProductResourceAliasStorageToSynchronizationServiceInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;

class PriceProductAbstractStorageReader implements PriceProductAbstractStorageReaderInterface
{
    protected const REFERENCE_NAME = 'sku:';

    /**
     * @var \Spryker\Client\PriceProductResourceAliasStorage\Dependency\Client\PriceProductResourceAliasStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\PriceProductResourceAliasStorage\Dependency\Service\PriceProductResourceAliasStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Client\PriceProductResourceAliasStorage\Dependency\Client\PriceProductResourceAliasStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\PriceProductResourceAliasStorage\Dependency\Service\PriceProductResourceAliasStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        PriceProductResourceAliasStorageToStorageClientInterface $storageClient,
        PriceProductResourceAliasStorageToSynchronizationServiceInterface $synchronizationService,
        Store $store
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceProductAbstractStorageTransfer(string $sku): ?PriceProductStorageTransfer
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setReference(static::REFERENCE_NAME . $sku)
            ->setStore($this->store->getStoreName());

        $key = $this->synchronizationService
            ->getStorageKeyBuilder(PriceProductStorageConstants::PRICE_ABSTRACT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);

        $mappingResource = $this->storageClient->get($key);
        if (!$mappingResource) {
            return null;
        }

        $priceProductAbstractStorageData = $this->storageClient->get($mappingResource['key']);
        if ($priceProductAbstractStorageData === null) {
            return null;
        }

        return (new PriceProductStorageTransfer)->fromArray($priceProductAbstractStorageData, true);
    }
}
