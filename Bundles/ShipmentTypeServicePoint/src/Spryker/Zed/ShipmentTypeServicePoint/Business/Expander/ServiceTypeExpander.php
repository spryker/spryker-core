<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Business\Expander;

use Generated\Shared\Transfer\ServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageTransfer;
use Spryker\Zed\ShipmentTypeServicePoint\Dependency\Facade\ShipmentTypeServicePointToServicePointFacadeInterface;
use Spryker\Zed\ShipmentTypeServicePoint\Persistence\ShipmentTypeServicePointRepositoryInterface;

class ServiceTypeExpander implements ServiceTypeExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeServicePoint\Persistence\ShipmentTypeServicePointRepositoryInterface
     */
    protected ShipmentTypeServicePointRepositoryInterface $shipmentTypeServicePointRepository;

    /**
     * @var \Spryker\Zed\ShipmentTypeServicePoint\Dependency\Facade\ShipmentTypeServicePointToServicePointFacadeInterface
     */
    protected ShipmentTypeServicePointToServicePointFacadeInterface $servicePointFacade;

    /**
     * @param \Spryker\Zed\ShipmentTypeServicePoint\Persistence\ShipmentTypeServicePointRepositoryInterface $shipmentTypeServicePointRepository
     * @param \Spryker\Zed\ShipmentTypeServicePoint\Dependency\Facade\ShipmentTypeServicePointToServicePointFacadeInterface $servicePointFacade
     */
    public function __construct(
        ShipmentTypeServicePointRepositoryInterface $shipmentTypeServicePointRepository,
        ShipmentTypeServicePointToServicePointFacadeInterface $servicePointFacade
    ) {
        $this->shipmentTypeServicePointRepository = $shipmentTypeServicePointRepository;
        $this->servicePointFacade = $servicePointFacade;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function expandShipmentTypeStoragesWithServiceType(array $shipmentTypeStorageTransfers): array
    {
        $shipmentTypeIds = $this->extractShipmentTypeIds($shipmentTypeStorageTransfers);
        $serviceTypeIdsIndexedByIdShipmentType = $this->shipmentTypeServicePointRepository->getServiceTypeIdsIndexedByIdShipmentType($shipmentTypeIds);

        if (!$serviceTypeIdsIndexedByIdShipmentType) {
            return $shipmentTypeStorageTransfers;
        }

        $serviceTypeTransfersIndexedByIdServiceType = $this->getServiceTypesIndexedByIdServiceType(
            array_unique(array_values($serviceTypeIdsIndexedByIdShipmentType)),
        );

        return $this->expandShipmentTypeStorages(
            $shipmentTypeStorageTransfers,
            $serviceTypeIdsIndexedByIdShipmentType,
            $serviceTypeTransfersIndexedByIdServiceType,
        );
    }

    /**
     * @param list<int> $serviceTypeIds
     *
     * @return array<int, \Generated\Shared\Transfer\ServiceTypeTransfer>
     */
    protected function getServiceTypesIndexedByIdServiceType(array $serviceTypeIds): array
    {
        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions((new ServiceTypeConditionsTransfer())->setServiceTypeIds($serviceTypeIds));

        $serviceTypeCollectionTransfer = $this->servicePointFacade->getServiceTypeCollection($serviceTypeCriteriaTransfer);

        $indexedServiceTypes = [];
        foreach ($serviceTypeCollectionTransfer->getServiceTypes() as $serviceTypeTransfer) {
            $indexedServiceTypes[$serviceTypeTransfer->getIdServiceTypeOrFail()] = $serviceTypeTransfer;
        }

        return $indexedServiceTypes;
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
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     * @param array<int, int> $serviceTypeIdsIndexedByIdShipmentType
     * @param array<int, \Generated\Shared\Transfer\ServiceTypeTransfer> $serviceTypeTransfersIndexedByIdServiceType
     *
     * @return list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer>
     */
    public function expandShipmentTypeStorages(
        array $shipmentTypeStorageTransfers,
        array $serviceTypeIdsIndexedByIdShipmentType,
        array $serviceTypeTransfersIndexedByIdServiceType
    ): array {
        foreach ($shipmentTypeStorageTransfers as $shipmentTypeStorageTransfer) {
            $idServiceType = $serviceTypeIdsIndexedByIdShipmentType[$shipmentTypeStorageTransfer->getIdShipmentTypeOrFail()] ?? null;
            if (!$idServiceType) {
                continue;
            }

            $serviceTypeTransfer = $serviceTypeTransfersIndexedByIdServiceType[$idServiceType] ?? null;
            if (!$serviceTypeTransfer) {
                continue;
            }

            $shipmentTypeStorageTransfer->setServiceType(
                (new ServiceTypeStorageTransfer())->setUuid($serviceTypeTransfer->getUuidOrFail()),
            );
        }

        return $shipmentTypeStorageTransfers;
    }
}
