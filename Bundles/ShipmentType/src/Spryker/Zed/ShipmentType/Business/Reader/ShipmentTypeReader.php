<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Reader;

use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Zed\ShipmentType\Business\Expander\ShipmentTypeStoreRelationshipExpanderInterface;
use Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface;

class ShipmentTypeReader implements ShipmentTypeReaderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface
     */
    protected ShipmentTypeRepositoryInterface $shipmentTypeRepository;

    /**
     * @var \Spryker\Zed\ShipmentType\Business\Expander\ShipmentTypeStoreRelationshipExpanderInterface
     */
    protected ShipmentTypeStoreRelationshipExpanderInterface $shipmentTypeStoreRelationshipExpander;

    /**
     * @param \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeRepositoryInterface $shipmentTypeRepository
     * @param \Spryker\Zed\ShipmentType\Business\Expander\ShipmentTypeStoreRelationshipExpanderInterface $shipmentTypeStoreRelationshipExpander
     */
    public function __construct(
        ShipmentTypeRepositoryInterface $shipmentTypeRepository,
        ShipmentTypeStoreRelationshipExpanderInterface $shipmentTypeStoreRelationshipExpander
    ) {
        $this->shipmentTypeRepository = $shipmentTypeRepository;
        $this->shipmentTypeStoreRelationshipExpander = $shipmentTypeStoreRelationshipExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getShipmentTypeCollection(
        ShipmentTypeCriteriaTransfer $shipmentTypeCriteriaTransfer
    ): ShipmentTypeCollectionTransfer {
        $shipmentTypeCollectionTransfer = $this->shipmentTypeRepository
            ->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        $shipmentTypeConditionsTransfer = $shipmentTypeCriteriaTransfer->getShipmentTypeConditions();
        if ($shipmentTypeConditionsTransfer && $shipmentTypeConditionsTransfer->getWithStoreRelations()) {
            $shipmentTypeCollectionTransfer = $this->shipmentTypeStoreRelationshipExpander
                ->expandShipmentTypeCollectionWithStoreRelationships($shipmentTypeCollectionTransfer);
        }

        return $shipmentTypeCollectionTransfer;
    }
}
