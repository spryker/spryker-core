<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentCarrierFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentGroupFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentCarrier\ShipmentCarrierFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\Constraint\ShipmentMethodKeyUniqueConstraint;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\Constraint\ShipmentMethodNameUniqueConstraint;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\ShipmentMethodForm;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\ViewShipmentMethodForm;
use Spryker\Zed\ShipmentGui\Communication\Form\Transformer\StringToNumberTransformer;
use Spryker\Zed\ShipmentGui\Communication\Mapper\ShipmentCarrierMapper;
use Spryker\Zed\ShipmentGui\Communication\Provider\ShipmentMethodFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Provider\ViewShipmentMethodFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Table\ShipmentMethodTable;
use Spryker\Zed\ShipmentGui\Communication\Tabs\ShipmentMethodTabs;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToCustomerFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToSalesFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface;
use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToTaxFacadeInterface;
use Spryker\Zed\ShipmentGui\ShipmentGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    public const KEY_AVAILABILITY = 'availability';
    public const KEY_PRICE = 'price';
    public const KEY_DELIVERY_TIME = 'delivery_time';

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
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentCarrierFormDataProvider
     */
    public function createShipmentCarrierFormDataProvider(): ShipmentCarrierFormDataProvider
    {
        return new ShipmentCarrierFormDataProvider($this->getShipmentFacade());
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createShipmentCarrierFormType(): FormInterface
    {
        $shipmentCarrierFormDataProvider = $this->createShipmentCarrierFormDataProvider();

        return $this->getFormFactory()->create(
            ShipmentCarrierFormType::class,
            $shipmentCarrierFormDataProvider->getData(),
            $shipmentCarrierFormDataProvider->getOptions()
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
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createViewShipmentMethodForm(?ShipmentMethodTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ViewShipmentMethodForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Provider\ViewShipmentMethodFormDataProvider
     */
    public function createViewShipmentMethodFormDataProvider(): ViewShipmentMethodFormDataProvider
    {
        return new ViewShipmentMethodFormDataProvider(
            $this->getTaxFacade()
        );
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
     * @return \Spryker\Zed\ShipmentGui\Communication\Mapper\ShipmentCarrierMapper
     */
    public function createShipmentCarrierMapper(): ShipmentCarrierMapper
    {
        return new ShipmentCarrierMapper();
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Table\ShipmentMethodTable
     */
    public function createShipmentMethodTable(): ShipmentMethodTable
    {
        return new ShipmentMethodTable($this->getShipmentMethodQuery());
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Tabs\ShipmentMethodTabs
     */
    public function createShipmentMethodTabs(): ShipmentMethodTabs
    {
        return new ShipmentMethodTabs();
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Provider\ShipmentMethodFormDataProvider
     */
    public function createShipmentMethodFormDataProvider(): ShipmentMethodFormDataProvider
    {
        return new ShipmentMethodFormDataProvider(
            $this->getPlugins(),
            $this->getShipmentCarrierQuery(),
            $this->getTaxFacade()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createShipmentMethodForm(ShipmentMethodTransfer $data, $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ShipmentMethodForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\Constraint\ShipmentMethodNameUniqueConstraint
     */
    public function createShipmentMethodNameUniqueConstraint(): ShipmentMethodNameUniqueConstraint
    {
        return new ShipmentMethodNameUniqueConstraint([
            ShipmentMethodNameUniqueConstraint::OPTION_SHIPMENT_FACADE => $this->getShipmentFacade(),
        ]);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\Constraint\ShipmentMethodKeyUniqueConstraint
     */
    public function createShipmentMethodKeyUniqueConstraint(): ShipmentMethodKeyUniqueConstraint
    {
        return new ShipmentMethodKeyUniqueConstraint([
            ShipmentMethodKeyUniqueConstraint::OPTION_SHIPMENT_FACADE => $this->getShipmentFacade(),
        ]);
    }

    /**
     * @return array
     */
    public function getPlugins(): array
    {
        return [
            static::KEY_AVAILABILITY => $this->getProvidedDependency(ShipmentGuiDependencyProvider::PLUGINS_AVAILABILITY),
            static::KEY_PRICE => $this->getProvidedDependency(ShipmentGuiDependencyProvider::PLUGINS_PRICE),
            static::KEY_DELIVERY_TIME => $this->getProvidedDependency(ShipmentGuiDependencyProvider::PLUGINS_DELIVERY_TIME),
        ];
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function getShipmentMethodQuery(): SpyShipmentMethodQuery
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::PROPEL_QUERY_SHIPMENT_METHOD);
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery
     */
    public function getShipmentCarrierQuery(): SpyShipmentCarrierQuery
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::PROPEL_QUERY_SHIPMENT_CARRIER);
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

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getMoneyCollectionFormTypePlugin(): FormTypeInterface
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::PLUGIN_MONEY_COLLECTION_FORM_TYPE);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE);
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToTaxFacadeInterface
     */
    public function getTaxFacade(): ShipmentGuiToTaxFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::FACADE_TAX);
    }
}
