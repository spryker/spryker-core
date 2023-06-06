<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage\Generator;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ShipmentTypeStorage\Dependency\Service\ShipmentTypeStorageToSynchronizationServiceInterface;
use Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig;

class ShipmentTypeStorageKeyGenerator implements ShipmentTypeStorageKeyGeneratorInterface
{
    /**
     * @var string
     */
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @var \Spryker\Client\ShipmentTypeStorage\Dependency\Service\ShipmentTypeStorageToSynchronizationServiceInterface
     */
    protected ShipmentTypeStorageToSynchronizationServiceInterface $synchronizationService;

    /**
     * @param \Spryker\Client\ShipmentTypeStorage\Dependency\Service\ShipmentTypeStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(ShipmentTypeStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @param list<int> $shipmentTypeIds
     * @param string $storeName
     *
     * @return list<string>
     */
    public function generateShipmentTypeStorageKeys(array $shipmentTypeIds, string $storeName): array
    {
        $storageKeys = [];
        foreach ($shipmentTypeIds as $idShipmentType) {
            $storageKeys[] = $this->generateKey((string)$idShipmentType, $storeName);
        }

        return $storageKeys;
    }

    /**
     * @param list<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return list<string>
     */
    public function generateShipmentTypeStorageUuidMappingKeys(array $shipmentTypeUuids, string $storeName): array
    {
        $storageKeys = [];
        foreach ($shipmentTypeUuids as $shipmentTypeUuid) {
            $reference = sprintf('%s:%s', static::MAPPING_TYPE_UUID, $shipmentTypeUuid);
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
            ->getStorageKeyBuilder(ShipmentTypeStorageConfig::SHIPMENT_TYPE_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
