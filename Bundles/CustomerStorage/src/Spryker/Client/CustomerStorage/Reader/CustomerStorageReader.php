<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerStorage\Reader;

use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CustomerStorage\Dependency\Client\CustomerStorageToStorageClientInterface;
use Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToSynchronizationServiceInterface;
use Spryker\Client\CustomerStorage\Mapper\CustomerStorageMapperInterface;
use Spryker\Shared\CustomerStorage\CustomerStorageConfig;

class CustomerStorageReader implements CustomerStorageReaderInterface
{
    /**
     * @var \Spryker\Client\CustomerStorage\Dependency\Client\CustomerStorageToStorageClientInterface
     */
    protected CustomerStorageToStorageClientInterface $storageClient;

    /**
     * @var \Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToSynchronizationServiceInterface
     */
    protected CustomerStorageToSynchronizationServiceInterface $synchronizationService;

    /**
     * @var \Spryker\Client\CustomerStorage\Mapper\CustomerStorageMapperInterface
     */
    protected CustomerStorageMapperInterface $customerStorageMapper;

    /**
     * @param \Spryker\Client\CustomerStorage\Dependency\Client\CustomerStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\CustomerStorage\Mapper\CustomerStorageMapperInterface $customerStorageMapper
     */
    public function __construct(
        CustomerStorageToStorageClientInterface $storageClient,
        CustomerStorageToSynchronizationServiceInterface $synchronizationService,
        CustomerStorageMapperInterface $customerStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->customerStorageMapper = $customerStorageMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    public function getInvalidatedCustomerCollection(
        InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
    ): InvalidatedCustomerCollectionTransfer {
        $customerReferences = $this->getCustomerReferencesFromInvalidatedCustomerCriteriaTransfer(
            $invalidatedCustomerCriteriaTransfer,
        );

        if ($customerReferences === []) {
            return new InvalidatedCustomerCollectionTransfer();
        }

        $storageKeysIndexedByCustomerReference = $this->getStorageKeysIndexedByCustomerReference($customerReferences);
        $customerInvalidatedStorageDataCollection = $this->storageClient->getMulti($storageKeysIndexedByCustomerReference);
        $customerInvalidatedStorageDataCollectionIndexedByCustomerReference = $this->getCustomerInvalidatedStorageDataCollectionIndexedByCustomerReference(
            $customerInvalidatedStorageDataCollection,
            $storageKeysIndexedByCustomerReference,
        );

        return $this->customerStorageMapper->mapCustomerInvalidatedStorageDataCollectionToInvalidatedCustomerCollectionTransfer(
            $customerInvalidatedStorageDataCollectionIndexedByCustomerReference,
            new InvalidatedCustomerCollectionTransfer(),
        );
    }

    /**
     * @param array<string, string> $customerInvalidatedStorageDataCollection
     * @param array<string, string> $storageKeysIndexedByCustomerReference
     *
     * @return array<string, string>
     */
    protected function getCustomerInvalidatedStorageDataCollectionIndexedByCustomerReference(
        array $customerInvalidatedStorageDataCollection,
        array $storageKeysIndexedByCustomerReference
    ): array {
        $customerInvalidatedStorageDataCollectionIndexedByCustomerReference = [];
        foreach ($customerInvalidatedStorageDataCollection as $storageKey => $customerInvalidatedStorageData) {
            $customerReference = $this->getCustomerReference($storageKey);

            if (!array_key_exists($customerReference, $storageKeysIndexedByCustomerReference)) {
                continue;
            }

            $customerInvalidatedStorageDataCollectionIndexedByCustomerReference[$customerReference] = $customerInvalidatedStorageData;
        }

        return $customerInvalidatedStorageDataCollectionIndexedByCustomerReference;
    }

    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
     *
     * @return array<int, string>
     */
    protected function getCustomerReferencesFromInvalidatedCustomerCriteriaTransfer(
        InvalidatedCustomerCriteriaTransfer $invalidatedCustomerCriteriaTransfer
    ): array {
        $invalidatedCustomerConditionsTransfer = $invalidatedCustomerCriteriaTransfer->getInvalidatedCustomerConditions();

        if ($invalidatedCustomerConditionsTransfer && $invalidatedCustomerConditionsTransfer->getCustomerReferences()) {
            return $invalidatedCustomerConditionsTransfer->getCustomerReferences();
        }

        return [];
    }

    /**
     * @param array<int, string> $customerReferences
     *
     * @return array<string, string>
     */
    protected function getStorageKeysIndexedByCustomerReference(array $customerReferences): array
    {
        $keys = [];
        foreach ($customerReferences as $customerReference) {
            $keys[$customerReference] = $this->generateKey($customerReference);
        }

        return $keys;
    }

    /**
     * @param string $storageKey
     *
     * @return string
     */
    protected function getCustomerReference(string $storageKey): string
    {
        $storageKeyArray = explode(':', $storageKey);

        return strtoupper(end($storageKeyArray));
    }

    /**
     * @param string $customerReference
     *
     * @return string
     */
    protected function generateKey(string $customerReference): string
    {
        return $this->synchronizationService
            ->getStorageKeyBuilder(CustomerStorageConfig::CUSTOMER_RESOURCE_NAME)
            ->generateKey(
                (new SynchronizationDataTransfer())->setReference($customerReference),
            );
    }
}
