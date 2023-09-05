<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage\Generator;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToSynchronizationServiceInterface;

class StorageKeyGenerator implements StorageKeyGeneratorInterface
{
    /**
     * @var string
     */
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @var \Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToSynchronizationServiceInterface
     */
    protected ServicePointStorageToSynchronizationServiceInterface $synchronizationService;

    /**
     * @param \Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(ServicePointStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param list<int> $resourceIds
     * @param string $resourceName
     * @param string|null $storeName
     *
     * @return list<string>
     */
    public function generateIdKeys(
        array $resourceIds,
        string $resourceName,
        ?string $storeName = null
    ): array {
        $storageKeys = [];
        foreach ($resourceIds as $idResource) {
            $storageKeys[] = $this->generateKey((string)$idResource, $resourceName, $storeName);
        }

        return $storageKeys;
    }

    /**
     * @param list<string> $uuids
     * @param string $resourceName
     * @param string|null $storeName
     *
     * @return list<string>
     */
    public function generateUuidKeys(
        array $uuids,
        string $resourceName,
        ?string $storeName = null
    ): array {
        $storageKeys = [];
        foreach ($uuids as $uuid) {
            $reference = sprintf('%s:%s', static::MAPPING_TYPE_UUID, $uuid);
            $storageKeys[] = $this->generateKey($reference, $resourceName, $storeName);
        }

        return $storageKeys;
    }

    /**
     * @param string $reference
     * @param string $resourceName
     * @param string|null $storeName
     *
     * @return string
     */
    protected function generateKey(string $reference, string $resourceName, ?string $storeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setStore($storeName)
            ->setReference($reference);

        return $this->synchronizationService
            ->getStorageKeyBuilder($resourceName)
            ->generateKey($synchronizationDataTransfer);
    }
}
