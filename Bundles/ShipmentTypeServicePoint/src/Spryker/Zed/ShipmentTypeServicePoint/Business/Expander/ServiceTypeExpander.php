<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Business\Expander;

use Generated\Shared\Transfer\ServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer;
use Spryker\Zed\ShipmentTypeServicePoint\Dependency\Facade\ShipmentTypeServicePointToServicePointFacadeInterface;

class ServiceTypeExpander implements ServiceTypeExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeServicePoint\Dependency\Facade\ShipmentTypeServicePointToServicePointFacadeInterface
     */
    protected ShipmentTypeServicePointToServicePointFacadeInterface $servicePointFacade;

    /**
     * @param \Spryker\Zed\ShipmentTypeServicePoint\Dependency\Facade\ShipmentTypeServicePointToServicePointFacadeInterface $servicePointFacade
     */
    public function __construct(ShipmentTypeServicePointToServicePointFacadeInterface $servicePointFacade)
    {
        $this->servicePointFacade = $servicePointFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer $shipmentTypeServiceTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer
     */
    public function expandShipmentTypeServiceTypeCollection(
        ShipmentTypeServiceTypeCollectionTransfer $shipmentTypeServiceTypeCollectionTransfer
    ): ShipmentTypeServiceTypeCollectionTransfer {
        $serviceTypeIds = $this->extractServiceTypeIds($shipmentTypeServiceTypeCollectionTransfer);
        $serviceTypeTransfersIndexedByIdServiceType = $this->getServiceTypesIndexedByIdServiceType($serviceTypeIds);

        foreach ($shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes() as $shipmentTypeServiceTypeTransfer) {
            $idServiceType = $shipmentTypeServiceTypeTransfer->getServiceTypeOrFail()->getIdServiceTypeOrFail();
            $serviceTypeTransfer = $serviceTypeTransfersIndexedByIdServiceType[$idServiceType] ?? null;
            if (!$serviceTypeTransfer) {
                continue;
            }

            $shipmentTypeServiceTypeTransfer->setServiceType($serviceTypeTransfer);
        }

        return $shipmentTypeServiceTypeCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer $shipmentTypeServiceTypeCollectionTransfer
     *
     * @return array<int, int>
     */
    protected function extractServiceTypeIds(ShipmentTypeServiceTypeCollectionTransfer $shipmentTypeServiceTypeCollectionTransfer): array
    {
        $serviceTypeIds = [];
        foreach ($shipmentTypeServiceTypeCollectionTransfer->getShipmentTypeServiceTypes() as $shipmentTypeServiceTypeTransfer) {
            $serviceTypeIds[] = $shipmentTypeServiceTypeTransfer->getServiceTypeOrFail()->getIdServiceTypeOrFail();
        }

        return array_unique($serviceTypeIds);
    }

    /**
     * @param array<int, int> $serviceTypeIds
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
}
