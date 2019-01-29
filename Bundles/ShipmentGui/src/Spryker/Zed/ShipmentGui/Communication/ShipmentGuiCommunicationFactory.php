<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormCreateDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentFormCreate;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface;
use Spryker\Zed\ShipmentGui\ShipmentGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormCreateDataProvider
     */
    public function createShipmentFormCreateDataProvider(): ShipmentFormCreateDataProvider
    {
        return new ShipmentFormCreateDataProvider(
            $this->getSalesFacade(),
            $this->getCustomerFacade(),
            $this->getShipmentFacade()
        );
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createShipmentFormCreate(array $formData, array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(
            ShipmentFormCreate::class,
            $formData,
            $formOptions
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesInterface
     */
    public function getSalesFacade(): ShipmentGuiToSalesInterface
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface
     */
    public function getShipmentFacade(): ShipmentGuiToShipmentInterface
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerInterface
     */
    public function getCustomerFacade(): ShipmentGuiToCustomerInterface
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::FACADE_CUSTOMER);
    }
}
