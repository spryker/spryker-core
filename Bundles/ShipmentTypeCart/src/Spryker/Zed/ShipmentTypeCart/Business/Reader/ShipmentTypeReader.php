<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business\Reader;

use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Zed\ShipmentTypeCart\Dependency\Facade\ShipmentTypeCartToShipmentTypeFacadeInterface;

class ShipmentTypeReader implements ShipmentTypeReaderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeCart\Dependency\Facade\ShipmentTypeCartToShipmentTypeFacadeInterface
     */
    protected ShipmentTypeCartToShipmentTypeFacadeInterface $shipmentTypeFacade;

    /**
     * @param \Spryker\Zed\ShipmentTypeCart\Dependency\Facade\ShipmentTypeCartToShipmentTypeFacadeInterface $shipmentTypeFacade
     */
    public function __construct(ShipmentTypeCartToShipmentTypeFacadeInterface $shipmentTypeFacade)
    {
        $this->shipmentTypeFacade = $shipmentTypeFacade;
    }

    /**
     * @param list<string> $shipmentTypeUuids
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function getActiveShipmentTypeCollection(array $shipmentTypeUuids, string $storeName): ShipmentTypeCollectionTransfer
    {
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->setUuids($shipmentTypeUuids)
            ->addStoreName($storeName)
            ->setIsActive(true);

        $shipmentTypeCriteriaTransfer = (new ShipmentTypeCriteriaTransfer())->setShipmentTypeConditions($shipmentTypeConditionsTransfer);

        return $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);
    }
}
