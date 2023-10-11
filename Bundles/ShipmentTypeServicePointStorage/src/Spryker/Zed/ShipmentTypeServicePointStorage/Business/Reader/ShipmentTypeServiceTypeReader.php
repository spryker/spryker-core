<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointStorage\Business\Reader;

use Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeServiceTypeCriteriaTransfer;
use Spryker\Zed\ShipmentTypeServicePointStorage\Dependency\Facade\ShipmentTypeServicePointStorageToShipmentTypeServicePointFacadeInterface;

class ShipmentTypeServiceTypeReader implements ShipmentTypeServiceTypeReaderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeServicePointStorage\Dependency\Facade\ShipmentTypeServicePointStorageToShipmentTypeServicePointFacadeInterface
     */
    protected ShipmentTypeServicePointStorageToShipmentTypeServicePointFacadeInterface $shipmentTypeServicePointFacade;

    /**
     * @param \Spryker\Zed\ShipmentTypeServicePointStorage\Dependency\Facade\ShipmentTypeServicePointStorageToShipmentTypeServicePointFacadeInterface $shipmentTypeServicePointFacade
     */
    public function __construct(ShipmentTypeServicePointStorageToShipmentTypeServicePointFacadeInterface $shipmentTypeServicePointFacade)
    {
        $this->shipmentTypeServicePointFacade = $shipmentTypeServicePointFacade;
    }

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer
     */
    public function getShipmentTypeServiceTypeCollection(array $shipmentTypeIds): ShipmentTypeServiceTypeCollectionTransfer
    {
        $shipmentTypeServiceTypeCriteriaTransfer = $this->createShipmentTypeServiceTypeCriteriaTransfer($shipmentTypeIds);

        return $this->shipmentTypeServicePointFacade->getShipmentTypeServiceTypeCollection(
            $shipmentTypeServiceTypeCriteriaTransfer,
        );
    }

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeServiceTypeCriteriaTransfer
     */
    protected function createShipmentTypeServiceTypeCriteriaTransfer(array $shipmentTypeIds): ShipmentTypeServiceTypeCriteriaTransfer
    {
        $shipmentTypeServiceTypeConditionsTransfer = (new ShipmentTypeServiceTypeConditionsTransfer())
            ->setShipmentTypeIds($shipmentTypeIds)
            ->setWithServiceTypeRelations(true);

        return (new ShipmentTypeServiceTypeCriteriaTransfer())
            ->setShipmentTypeServiceTypeConditions($shipmentTypeServiceTypeConditionsTransfer);
    }
}
