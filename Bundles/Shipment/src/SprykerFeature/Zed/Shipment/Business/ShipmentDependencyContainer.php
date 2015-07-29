<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Shipment\Business;


use Generated\Zed\Ide\FactoryAutoCompletion\ShipmentBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Shipment\Business\Model\Carrier;
use SprykerFeature\Zed\Shipment\Business\Model\Method;

/**
 * @method ShipmentBusiness getFactory()
*/
class ShipmentDependencyContainer extends AbstractBusinessDependencyContainer
{
    /**
     * @return Carrier
     */
    public function createCarrierModel()
    {
        return $this->getFactory()->createModelCarrier();
    }
    /**
     * @return Method
     */
    public function createMethodModel()
    {
        return $this->getFactory()->createModelMethod();
    }
}
