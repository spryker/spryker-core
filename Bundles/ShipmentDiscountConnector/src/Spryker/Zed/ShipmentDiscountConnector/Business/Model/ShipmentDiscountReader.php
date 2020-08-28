<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Business\Model;

use Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentInterface;

class ShipmentDiscountReader implements ShipmentDiscountReaderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentInterface
     */
    protected $shipmentFacade;

    /**
     * @param \Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade\ShipmentDiscountConnectorToShipmentInterface $shipmentFacade
     */
    public function __construct(ShipmentDiscountConnectorToShipmentInterface $shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @return string[]
     */
    public function getCarrierList()
    {
        $shipmentCarrierTransfers = $this->shipmentFacade->getCarriers();

        $list = [];
        foreach ($shipmentCarrierTransfers as $shipmentCarrierTransfer) {
            $list[$shipmentCarrierTransfer->getIdShipmentCarrier()] = $shipmentCarrierTransfer->getName();
        }

        return $list;
    }

    /**
     * @return string[]
     */
    public function getMethodList()
    {
        $shipmentMethodTransfers = $this->shipmentFacade->getMethods();

        $list = [];
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $list[$shipmentMethodTransfer->getIdShipmentMethod()] = $shipmentMethodTransfer->getName() .
                ' (' . $shipmentMethodTransfer->getCarrierName() . ')';
        }

        return $list;
    }
}
