<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Shipment;

use SprykerFeature\Client\Shipment\Zed\ShipmentStub;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Shipment\Zed\ShipmentStubInterface;
use SprykerFeature\Client\Shipment\ShipmentDependencyProvider;

class ShipmentDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return ShipmentStubInterface
     */
    public function createZedStub()
    {
        $zedStub = $this->getProvidedDependency(ShipmentDependencyProvider::SERVICE_ZED);
        $cartStub = new ShipmentStub($zedStub);

        return $cartStub;
    }

}
