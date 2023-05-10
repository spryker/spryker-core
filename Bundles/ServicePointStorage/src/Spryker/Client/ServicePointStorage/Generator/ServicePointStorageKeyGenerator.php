<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage\Generator;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ServicePointStorage\Dependency\Service\ServicePointStorageToSynchronizationServiceInterface;
use Spryker\Shared\ServicePointStorage\ServicePointStorageConfig;

class ServicePointStorageKeyGenerator implements ServicePointStorageKeyGeneratorInterface
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
     * @param list<int> $servicePointIds
     * @param string $storeName
     *
     * @return list<string>
     */
    public function generateServicePointIdKeys(array $servicePointIds, string $storeName): array
    {
        $storageKeys = [];
        foreach ($servicePointIds as $servicePointId) {
            $storageKeys[] = $this->generateKey((string)$servicePointId, $storeName);
        }

        return $storageKeys;
    }

    /**
     * @param list<string> $uuids
     * @param string $storeName
     *
     * @return list<string>
     */
    public function generateUuidKeys(array $uuids, string $storeName): array
    {
        $storageKeys = [];
        foreach ($uuids as $uuid) {
            $reference = sprintf('%s:%s', static::MAPPING_TYPE_UUID, $uuid);
            $storageKeys[] = $this->generateKey($reference, $storeName);
        }

        return $storageKeys;
    }

    /**
     * @param string $reference
     * @param string $storeName
     *
     * @return string
     */
    protected function generateKey(string $reference, string $storeName): string
    {
        $synchronizationDataTransfer = (new SynchronizationDataTransfer())
            ->setStore($storeName)
            ->setReference($reference);

        return $this->synchronizationService
            ->getStorageKeyBuilder(ServicePointStorageConfig::SERVICE_POINT_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
