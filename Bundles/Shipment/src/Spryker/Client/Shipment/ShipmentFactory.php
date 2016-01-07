<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Shipment;

use Spryker\Client\Shipment\Zed\ShipmentStub;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Shipment\Zed\ShipmentStubInterface;

class ShipmentFactory extends AbstractFactory
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
