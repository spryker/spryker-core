<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\ShipmentGui\Communication\Extractor\ShipmentOrderItemAlternativeTemplateProvider;
use Spryker\Zed\ShipmentGui\Communication\Extractor\ShipmentOrderItemAlternativeTemplateProviderInterface;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentCarrierFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentMethodFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ViewShipmentMethodFormDataProvider;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentGroupFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentMethodDeleteForm;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentCarrier\ShipmentCarrierFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\Constraint\ShipmentMethodKeyUniqueConstraint;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\Constraint\ShipmentMethodNameUniqueConstraint;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\ShipmentMethodForm;
use Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\ViewShipmentMethodForm;
use Spryker\Zed\ShipmentGui\Communication\Form\Transformer\StringToNumberTransformer;
use Spryker\Zed\ShipmentGui\Communication\Grouper\ProductBundleGrouper;
use Spryker\Zed\ShipmentGui\Communication\Grouper\ProductBundleGrouperInterface;
use Spryker\Zed\ShipmentGui\Communication\Mapper\ShipmentCarrierMapper;
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
     * @return \Spryker\Zed\ShipmentGui\Communication\Grouper\ProductBundleGrouperInterface
     */
    public function createProductBundleGrouper(): ProductBundleGrouperInterface
    {
        return new ProductBundleGrouper();
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
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ViewShipmentMethodFormDataProvider
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
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createShipmentMethodDeleteForm(): FormInterface
    {
        return $this->getFormFactory()->create(ShipmentMethodDeleteForm::class);
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
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\DataProvider\ShipmentMethodFormDataProvider
     */
    public function createShipmentMethodFormDataProvider(): ShipmentMethodFormDataProvider
    {
        return new ShipmentMethodFormDataProvider(
            $this->getShipmentFacade(),
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
     * @return \Spryker\Zed\ShipmentGui\Communication\Extractor\ShipmentOrderItemAlternativeTemplateProviderInterface
     */
    public function createShipmentOrderItemAlternativeTemplateProvider(): ShipmentOrderItemAlternativeTemplateProviderInterface
    {
        return new ShipmentOrderItemAlternativeTemplateProvider($this->getShipmentOrderItemTemplatePlugins());
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function getShipmentMethodQuery(): SpyShipmentMethodQuery
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::PROPEL_QUERY_SHIPMENT_METHOD);
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

    /**
     * @return \Spryker\Zed\ShipmentGuiExtension\Dependency\Plugin\ShipmentOrderItemTemplatePluginInterface[]
     */
    public function getShipmentOrderItemTemplatePlugins(): array
    {
        return $this->getProvidedDependency(ShipmentGuiDependencyProvider::PLUGIN_SHIPMENT_ORDER_ITEM_TEMPLATE);
    }
}
