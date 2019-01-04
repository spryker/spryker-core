<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication;

use Generated\Shared\Transfer\ShipmentFormTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\AddressFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShippingFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentForm;
use Spryker\Zed\ShipmentGui\ShipmentGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentGui\Persistence\ShipmentGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 * @method \Spryker\Zed\ShipmentGui\Business\ShipmentGuiFacadeInterface getFacade()
 */
class ShipmentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider
     */
    public function createAddressFormDataProvider()
    {
        return new AddressFormDataProvider(
            $this->getRepository(),
            $this->getCountryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCountryInterface
     */
    public function getCountryFacade()
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentInterface
     */
    public function getShipmentFacade()
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAddressForm(array $formData = [], array $formOptions = [])
    {
        return $this->getFormFactory()->create(AddressForm::class, $formData, $formOptions);
    }

    public function createShippingFormDataProvider()
    {
        return new ShippingFormDataProvider(
            $this->getRepository(),
            $this->createAddressFormDataProvider(),
            $this->getShipmentFacade()
        );
    }

    public function getShipmentFormTransfer(): ?ShipmentFormTransfer
    {
        return $this->createAddressFormDataProvider()->getShipmentFormTransfer();
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getShipmentForm(ShipmentFormTransfer $shipmentFormTransfer, $options)
    {
        return $this->getFormFactory()->create(
            ShipmentForm::class,
            $shipmentFormTransfer,
            $options
        );
    }
}
