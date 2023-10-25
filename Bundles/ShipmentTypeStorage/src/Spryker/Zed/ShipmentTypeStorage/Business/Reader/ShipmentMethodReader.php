<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Business\Reader;

use Generated\Shared\Transfer\ShipmentMethodCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodConditionsTransfer;
use Generated\Shared\Transfer\ShipmentMethodCriteriaTransfer;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentFacadeInterface;

class ShipmentMethodReader implements ShipmentMethodReaderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentFacadeInterface
     */
    protected ShipmentTypeStorageToShipmentFacadeInterface $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(ShipmentTypeStorageToShipmentFacadeInterface $shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function getActiveShipmentMethodCollectionTransferForStore(string $storeName): ShipmentMethodCollectionTransfer
    {
        $shipmentMethodConditionsTransfer = (new ShipmentMethodConditionsTransfer())
            ->addStoreName($storeName)
            ->setIsActive(true)
            ->setIsActiveShipmentCarrier(true);
        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())->setShipmentMethodConditions($shipmentMethodConditionsTransfer);

        return $this->shipmentFacade->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);
    }

    /**
     * @param list<int> $shipmentMethodIds
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function getShipmentMethodCollectionByShipmentMethodIds(array $shipmentMethodIds): ShipmentMethodCollectionTransfer
    {
        $shipmentMethodConditionsTransfer = (new ShipmentMethodConditionsTransfer())->setShipmentMethodIds($shipmentMethodIds);
        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())->setShipmentMethodConditions($shipmentMethodConditionsTransfer);

        return $this->shipmentFacade->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);
    }

    /**
     * @param list<int> $shipmentCarrierIds
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodCollectionTransfer
     */
    public function getShipmentMethodCollectionByShipmentCarrierIds(array $shipmentCarrierIds): ShipmentMethodCollectionTransfer
    {
        $shipmentMethodConditionsTransfer = (new ShipmentMethodConditionsTransfer())->setShipmentCarrierIds($shipmentCarrierIds);
        $shipmentMethodCriteriaTransfer = (new ShipmentMethodCriteriaTransfer())->setShipmentMethodConditions($shipmentMethodConditionsTransfer);

        return $this->shipmentFacade->getShipmentMethodCollection($shipmentMethodCriteriaTransfer);
    }
}
