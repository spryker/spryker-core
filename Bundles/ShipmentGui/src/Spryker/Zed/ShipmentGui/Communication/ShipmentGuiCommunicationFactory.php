<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormCreateDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDataProviderInterface;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProviderInterface;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormEditDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentFormCreate;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentFormEdit;
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
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDataProviderInterface
     */
    public function createShipmentFormCreateDataProvider(): ShipmentFormDataProviderInterface
    {
        return new ShipmentFormCreateDataProvider($this->createShipmentFormDefaultDataProvider());
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDataProviderInterface
     */
    public function createShipmentFormEditDataProvider(): ShipmentFormDataProviderInterface
    {
        return new ShipmentFormEditDataProvider($this->createShipmentFormDefaultDataProvider());
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDefaultDataProviderInterface
     */
    public function createShipmentFormDefaultDataProvider(): ShipmentFormDefaultDataProviderInterface
    {
        return new ShipmentFormDefaultDataProvider(
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
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createShipmentFormEdit(array $formData, array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(
            ShipmentFormEdit::class,
            $formData,
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
}
