<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface;

class ShipmentTypeStoreRelationshipExpander implements ShipmentTypeStoreRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface
     */
    protected ShipmentTypeRepositoryInterface $shipmentTypeRepository;

    /**
     * @param \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface $shipmentTypeRepository
     */
    public function __construct(ShipmentTypeRepositoryInterface $shipmentTypeRepository)
    {
        $this->shipmentTypeRepository = $shipmentTypeRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function expandShipmentTypeCollectionWithStoreRelationships(
        ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
    ): ShipmentTypeCollectionTransfer {
        $shipmentTypeIds = $this->extractShipmentTypeIdsFromShipmentTypeTransfers($shipmentTypeCollectionTransfer->getShipmentTypes());
        $storeRelationTransfers = $this->shipmentTypeRepository->getShipmentTypeStoreRelationsIndexedByIdShipmentType($shipmentTypeIds);

        foreach ($shipmentTypeCollectionTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $idShipmentType = $shipmentTypeTransfer->getIdShipmentTypeOrFail();
            if (!isset($storeRelationTransfers[$idShipmentType])) {
                continue;
            }

            $shipmentTypeTransfer->setStoreRelation($storeRelationTransfers[$idShipmentType]);
        }

        return $shipmentTypeCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return list<int>
     */
    protected function extractShipmentTypeIdsFromShipmentTypeTransfers(ArrayObject $shipmentTypeTransfers): array
    {
        $shipmentTypeIds = [];
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $shipmentTypeIds[] = $shipmentTypeTransfer->getIdShipmentTypeOrFail();
        }

        return $shipmentTypeIds;
    }
}
