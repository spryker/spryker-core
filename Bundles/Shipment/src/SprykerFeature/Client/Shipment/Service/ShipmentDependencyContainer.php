<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Shipment\Service;

use Generated\Client\Ide\FactoryAutoCompletion\ShipmentService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Shipment\Service\Zed\ShipmentStubInterface;
use SprykerFeature\Client\Shipment\ShipmentDependencyProvider;

/**
 * @method ShipmentService getFactory()
 */
class ShipmentDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return ShipmentStubInterface
     */
    public function createZedStub()
    {
        $zedStub = $this->getProvidedDependency(ShipmentDependencyProvider::SERVICE_ZED);
        $cartStub = $this->getFactory()
            ->createZedShipmentStub($zedStub)
        ;

        return $cartStub;
    }

}
