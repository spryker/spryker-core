<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerTest\Shared\Shipment\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ShipmentCarrierBuilder;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ShipmentCarrierDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    public function haveShipmentCarrier(array $override = [])
    {
        /** @var \Generated\Shared\Transfer\ShipmentCarrierTransfer $shipmentCarrierTransfer */
        $shipmentCarrierTransfer = (new ShipmentCarrierBuilder($override))->build();
        $shipmentCarrierTransfer->setIdShipmentCarrier(
            $this->getShipmentFacade()->createCarrier($shipmentCarrierTransfer)
        );

        return $shipmentCarrierTransfer;
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    protected function getShipmentFacade()
    {
        return $this->getLocator()->shipment()->facade();
    }
}
