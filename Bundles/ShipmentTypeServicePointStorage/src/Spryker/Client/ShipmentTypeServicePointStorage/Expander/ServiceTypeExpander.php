<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeServicePointStorage\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Spryker\Client\ShipmentTypeServicePointStorage\Reader\ServiceTypeReaderInterface;

class ServiceTypeExpander implements ServiceTypeExpanderInterface
{
    /**
     * @var \Spryker\Client\ShipmentTypeServicePointStorage\Reader\ServiceTypeReaderInterface
     */
    protected ServiceTypeReaderInterface $serviceTypeReader;

    /**
     * @param \Spryker\Client\ShipmentTypeServicePointStorage\Reader\ServiceTypeReaderInterface $serviceTypeReader
     */
    public function __construct(ServiceTypeReaderInterface $serviceTypeReader)
    {
        $this->serviceTypeReader = $serviceTypeReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    public function expandShipmentTypeStorageCollectionWithServiceType(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
    ): ShipmentTypeStorageCollectionTransfer {
        $serviceTypeUuids = $this->extractServiceTypeUuids($shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
        if (!$serviceTypeUuids) {
            return $shipmentTypeStorageCollectionTransfer;
        }
        $serviceTypeStorageCollectionTransfer = $this->serviceTypeReader->getServiceTypeStorageCollectionByUuids($serviceTypeUuids);
        $serviceTypeStorageTransfersIndexedByUuid = $this->getServiceTypeStorageTransfersIndexedByUuid(
            $serviceTypeStorageCollectionTransfer->getServiceTypeStorages(),
        );

        return $this->expandShipmentTypeStorageCollection(
            $shipmentTypeStorageCollectionTransfer,
            $serviceTypeStorageTransfersIndexedByUuid,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return array<int, string>
     */
    protected function extractServiceTypeUuids(ArrayObject $shipmentTypeStorageTransfers): array
    {
        $serviceTypeUuids = [];
        foreach ($shipmentTypeStorageTransfers as $shipmentTypeStorageTransfer) {
            if (!$shipmentTypeStorageTransfer->getServiceType()) {
                continue;
            }

            $serviceTypeUuids[] = $shipmentTypeStorageTransfer->getServiceTypeOrFail()->getUuidOrFail();
        }

        return array_unique($serviceTypeUuids);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeStorageTransfer> $serviceTypeStorageTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ServiceTypeStorageTransfer>
     */
    protected function getServiceTypeStorageTransfersIndexedByUuid(ArrayObject $serviceTypeStorageTransfers): array
    {
        $indexedServiceTypeStorageTransfers = [];
        foreach ($serviceTypeStorageTransfers as $serviceTypeStorageTransfer) {
            $indexedServiceTypeStorageTransfers[$serviceTypeStorageTransfer->getUuidOrFail()] = $serviceTypeStorageTransfer;
        }

        return $indexedServiceTypeStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer
     * @param array<string, \Generated\Shared\Transfer\ServiceTypeStorageTransfer> $serviceTypeStorageTransfersIndexedByUuid
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    protected function expandShipmentTypeStorageCollection(
        ShipmentTypeStorageCollectionTransfer $shipmentTypeStorageCollectionTransfer,
        array $serviceTypeStorageTransfersIndexedByUuid
    ): ShipmentTypeStorageCollectionTransfer {
        foreach ($shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages() as $shipmentTypeStorageTransfer) {
            $serviceTypeStorageTransfer = $shipmentTypeStorageTransfer->getServiceType();

            if (!$serviceTypeStorageTransfer || !$serviceTypeStorageTransfer->getUuid()) {
                continue;
            }

            $serviceTypeUuid = $shipmentTypeStorageTransfer->getServiceTypeOrFail()->getUuidOrFail();
            if (!isset($serviceTypeStorageTransfersIndexedByUuid[$serviceTypeUuid])) {
                continue;
            }

            $shipmentTypeStorageTransfer->setServiceType(
                $serviceTypeStorageTransfersIndexedByUuid[$serviceTypeUuid],
            );
        }

        return $shipmentTypeStorageCollectionTransfer;
    }
}
