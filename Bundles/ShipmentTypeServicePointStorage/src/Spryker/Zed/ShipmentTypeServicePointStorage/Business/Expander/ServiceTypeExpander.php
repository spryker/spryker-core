<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointStorage\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ServiceTypeStorageTransfer;
use Spryker\Zed\ShipmentTypeServicePointStorage\Business\Reader\ShipmentTypeServiceTypeReaderInterface;

class ServiceTypeExpander implements ServiceTypeExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeServicePointStorage\Business\Reader\ShipmentTypeServiceTypeReaderInterface
     */
    protected ShipmentTypeServiceTypeReaderInterface $shipmentTypeServicePointReader;

    /**
     * @param \Spryker\Zed\ShipmentTypeServicePointStorage\Business\Reader\ShipmentTypeServiceTypeReaderInterface $shipmentTypeServicePointReader
     */
    public function __construct(ShipmentTypeServiceTypeReaderInterface $shipmentTypeServicePointReader)
    {
        $this->shipmentTypeServicePointReader = $shipmentTypeServicePointReader;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function expandShipmentTypeStorages(array $shipmentTypeStorageTransfers): array
    {
        $shipmentTypeIds = $this->extractShipmentTypeIds($shipmentTypeStorageTransfers);
        $shipmentTypeServiceTypeCollectionTransfer = $this->shipmentTypeServicePointReader->getShipmentTypeServiceTypeCollection(
            $shipmentTypeIds,
        );
        $serviceTypeUuidsIndexedByIdShipmentType = $this->getServiceTypeUuidsIndexedByIdShipmentType(
            $shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes(),
        );

        return $this->expandShipmentTypeStoragesWithServiceTypeUuids(
            $shipmentTypeStorageTransfers,
            $serviceTypeUuidsIndexedByIdShipmentType,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return list<int>
     */
    protected function extractShipmentTypeIds(array $shipmentTypeStorageTransfers): array
    {
        $shipmentTypeIds = [];
        foreach ($shipmentTypeStorageTransfers as $shipmentTypeStorageTransfer) {
            $shipmentTypeIds[] = $shipmentTypeStorageTransfer->getIdShipmentTypeOrFail();
        }

        return $shipmentTypeIds;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeServiceTypeTransfer> $shipmentTypeServiceTypeTransfers
     *
     * @return array<int, string>
     */
    protected function getServiceTypeUuidsIndexedByIdShipmentType(ArrayObject $shipmentTypeServiceTypeTransfers): array
    {
        $serviceTypeUuidsIndexedByIdShipmentType = [];
        foreach ($shipmentTypeServiceTypeTransfers as $shipmentTypeServiceTypeTransfer) {
            $idShipmentType = $shipmentTypeServiceTypeTransfer->getShipmentTypeOrFail()->getIdShipmentTypeOrFail();
            $serviceTypeUuid = $shipmentTypeServiceTypeTransfer->getServiceTypeOrFail()->getUuidOrFail();
            $serviceTypeUuidsIndexedByIdShipmentType[$idShipmentType] = $serviceTypeUuid;
        }

        return $serviceTypeUuidsIndexedByIdShipmentType;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     * @param array<int, string> $serviceTypeUuidsIndexedByIdShipmentType
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    protected function expandShipmentTypeStoragesWithServiceTypeUuids(
        array $shipmentTypeStorageTransfers,
        array $serviceTypeUuidsIndexedByIdShipmentType
    ): array {
        foreach ($shipmentTypeStorageTransfers as $shipmentTypeStorageTransfer) {
            $idShipmentType = $shipmentTypeStorageTransfer->getIdShipmentTypeOrFail();
            $serviceTypeUuid = $serviceTypeUuidsIndexedByIdShipmentType[$idShipmentType] ?? null;
            if (!$serviceTypeUuid) {
                continue;
            }

            $shipmentTypeStorageTransfer->setServiceType(
                (new ServiceTypeStorageTransfer())->setUuid($serviceTypeUuid),
            );
        }

        return $shipmentTypeStorageTransfers;
    }
}
