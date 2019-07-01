<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentGroupFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Transformer\StringToNumberTransformer;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface;
use Spryker\Zed\ShipmentGui\ShipmentGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDataProvider
     */
    public function createShipmentFormDataProvider(): ShipmentFormDataProvider
    {
        return new ShipmentFormDataProvider(
            $this->getSalesFacade(),
            $this->getCustomerFacade(),
            $this->getShipmentFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createShipmentCreateForm(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        array $formOptions = []
    ): FormInterface {
        return $this->getFormFactory()->create(ShipmentGroupFormType::class, $shipmentGroupTransfer, $formOptions);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createShipmentEditForm(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        array $formOptions = []
    ): FormInterface {
        return $this->getFormFactory()->create(ShipmentGroupFormType::class, $shipmentGroupTransfer, $formOptions);
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createStringToNumberTransformer(): DataTransformerInterface
    {
        return new StringToNumberTransformer();
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
