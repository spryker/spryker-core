<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Shipment\Business\Model\Carrier;
use Spryker\Zed\Shipment\Business\Model\Method;
use Spryker\Zed\Shipment\Business\Model\ShipmentOrderSaver;
use Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\Tax\Business\TaxFacade;

/**
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 */
class ShipmentBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Shipment\Business\Model\Carrier
     */
    public function createCarrier()
    {
        return new Carrier();
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Model\Method
     */
    public function createMethod()
    {
        return new Method(
            $this->getQueryContainer(),
            $this->getProvidedDependency(ShipmentDependencyProvider::PLUGINS)
        );
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\Model\ShipmentOrderSaver
     */
    public function createShipmentOrderSaver()
    {
        return new ShipmentOrderSaver($this->getSalesQueryContainer());
    }

    /**
     * @return ShipmentTaxRateCalculator
     */
    public function createShipmentTaxCalculator()
    {
        return new ShipmentTaxRateCalculator($this->getQueryContainer(), $this->getTaxFacade());
    }

    /**
     * @return ShipmentToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(ShipmentDependencyProvider::QUERY_CONTAINER_SALES);
    }

}
