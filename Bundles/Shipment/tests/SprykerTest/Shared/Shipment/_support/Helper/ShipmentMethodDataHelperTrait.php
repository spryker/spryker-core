<?php

namespace SprykerTest\Shared\Shipment\Helper;

use Codeception\Module;

trait ShipmentMethodDataHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Shipment\Helper\ShipmentMethodDataHelper
     */
    protected function getShipmentMethodDataHelper(): ShipmentMethodDataHelper
    {
        /** @var \SprykerTest\Shared\Shipment\Helper\ShipmentMethodDataHelper $shipmentMethodDataHelper */
        $shipmentMethodDataHelper = $this->getModule('\\' . ShipmentMethodDataHelper::class);

        return $shipmentMethodDataHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
