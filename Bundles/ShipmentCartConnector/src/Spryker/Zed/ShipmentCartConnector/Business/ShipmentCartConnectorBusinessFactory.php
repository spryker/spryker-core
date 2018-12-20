<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartExpander;
use Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartExpanderHelper;
use Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartValidator;
use Spryker\Zed\ShipmentCartConnector\ShipmentCartConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentCartConnector\ShipmentCartConnectorConfig getConfig()
 */
class ShipmentCartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartExpanderInterface
     */
    public function createShipmentCartExpander()
    {
        return new ShipmentCartExpander(
            $this->getShipmentFacade(),
            $this->getPriceFacade(),
            $this->createShipmentCartExpanderHelper()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartValidatorInterface
     */
    public function createShipmentCartValidate()
    {
        return new ShipmentCartValidator(
            $this->getShipmentFacade(),
            $this->getPriceFacade(),
            $this->createShipmentCartExpanderHelper()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeInterface
     */
    public function getShipmentFacade()
    {
        return $this->getProvidedDependency(ShipmentCartConnectorDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(ShipmentCartConnectorDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Model\ShipmentCartExpanderHelper
     */
    public function createShipmentCartExpanderHelper(): ShipmentCartExpanderHelper
    {
        return new ShipmentCartExpanderHelper();
    }
}
