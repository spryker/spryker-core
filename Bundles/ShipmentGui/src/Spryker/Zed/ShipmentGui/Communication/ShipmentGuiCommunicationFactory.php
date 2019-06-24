<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication;

use Generated\Shared\Transfer\ShipmentFormTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentCreateFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentEditFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentCreateForm;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentEditForm;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface;
use Spryker\Zed\ShipmentGui\ShipmentGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentCreateFormDataProvider
     */
    public function createShipmentCreateFormDataProvider(): ShipmentCreateFormDataProvider
    {
        return new ShipmentCreateFormDataProvider($this->createShipmentFormDefaultDataProvider());
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentEditFormDataProvider
     */
    public function createShipmentEditFormDataProvider(): ShipmentEditFormDataProvider
    {
        return new ShipmentEditFormDataProvider($this->createShipmentFormDefaultDataProvider());
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProvider
     */
    public function createShipmentFormDefaultDataProvider(): ShipmentFormDefaultDataProvider
    {
        return new ShipmentFormDefaultDataProvider(
            $this->getSalesFacade(),
            $this->getCustomerFacade(),
            $this->getShipmentFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentFormTransfer $shipmentFormTransfer
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createShipmentCreateForm(ShipmentFormTransfer $shipmentFormTransfer, array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(
            ShipmentCreateForm::class,
            $shipmentFormTransfer,
            $formOptions
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentFormTransfer $shipmentFormTransfer
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createShipmentEditForm(ShipmentFormTransfer $shipmentFormTransfer, array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(
            ShipmentEditForm::class,
            $shipmentFormTransfer,
            $formOptions
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesFacadeInterface
     */
    public function getSalesFacade(): ShipmentGuiToSalesFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface
     */
    public function getShipmentFacade(): ShipmentGuiToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): ShipmentGuiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Service\ShipmentGuiToShipmentServiceInterface
     */
    public function getShipmentService()
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::SERVICE_SHIPMENT);
    }
}
