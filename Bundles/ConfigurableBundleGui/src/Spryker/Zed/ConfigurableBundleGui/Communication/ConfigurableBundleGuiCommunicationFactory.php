<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\ConfigurableBundleGui\Communication\Expander\ConfigurableBundleTemplateSlotEditFormExpander;
use Spryker\Zed\ConfigurableBundleGui\Communication\Expander\ConfigurableBundleTemplateSlotEditFormExpanderInterface;
use Spryker\Zed\ConfigurableBundleGui\Communication\Expander\ProductListButtonsExpander;
use Spryker\Zed\ConfigurableBundleGui\Communication\Expander\ProductListButtonsExpanderInterface;
use Spryker\Zed\ConfigurableBundleGui\Communication\Expander\ProductListUsedByTableDataExpander;
use Spryker\Zed\ConfigurableBundleGui\Communication\Expander\ProductListUsedByTableDataExpanderInterface;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateForm;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateSlotCreateForm;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateSlotEditForm;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider\ConfigurableBundleTemplateFormDataProvider;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider\ConfigurableBundleTemplateSlotCreateFormDataProvider;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider\ConfigurableBundleTemplateSlotEditFormDataProvider;
use Spryker\Zed\ConfigurableBundleGui\Communication\Handler\ConfigurableBundleTemplateSlotEditFormFileUploadHandler;
use Spryker\Zed\ConfigurableBundleGui\Communication\Handler\ConfigurableBundleTemplateSlotEditFormFileUploadHandlerInterface;
use Spryker\Zed\ConfigurableBundleGui\Communication\Mapper\ProductListUsedByTableDataMapper;
use Spryker\Zed\ConfigurableBundleGui\Communication\Mapper\ProductListUsedByTableDataMapperInterface;
use Spryker\Zed\ConfigurableBundleGui\Communication\Provider\ProductConcreteRelationSubTabsProvider;
use Spryker\Zed\ConfigurableBundleGui\Communication\Provider\ProductConcreteRelationSubTabsProviderInterface;
use Spryker\Zed\ConfigurableBundleGui\Communication\Provider\ProductConcreteRelationTablesProvider;
use Spryker\Zed\ConfigurableBundleGui\Communication\Provider\ProductConcreteRelationTablesProviderInterface;
use Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateSlotProductsTable;
use Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateSlotTable;
use Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateTable;
use Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateCreateTabs;
use Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateEditTabs;
use Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateSlotCreateTabs;
use Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateSlotEditTabs;
use Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiDependencyProvider;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToProductListFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\Persistence\ConfigurableBundleGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundleGui\Persistence\ConfigurableBundleGuiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiConfig getConfig()
 */
class ConfigurableBundleGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getConfigurableBundleTemplateForm(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        array $options = []
    ): FormInterface {
        return $this->getFormFactory()->create(
            ConfigurableBundleTemplateForm::class,
            $configurableBundleTemplateTransfer,
            $options
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getConfigurableBundleTemplateSlotCreateForm(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer,
        array $options = []
    ): FormInterface {
        return $this->getFormFactory()->create(
            ConfigurableBundleTemplateSlotCreateForm::class,
            $configurableBundleTemplateSlotTransfer,
            $options
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getConfigurableBundleTemplateSlotEditForm(
        ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer,
        array $options = []
    ): FormInterface {
        return $this->getFormFactory()->create(
            ConfigurableBundleTemplateSlotEditForm::class,
            $configurableBundleTemplateSlotEditFormTransfer,
            $options
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider\ConfigurableBundleTemplateFormDataProvider
     */
    public function createConfigurableBundleTemplateFormDataProvider(): ConfigurableBundleTemplateFormDataProvider
    {
        return new ConfigurableBundleTemplateFormDataProvider(
            $this->getConfigurableBundleFacade(),
            $this->getLocaleFacade(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider\ConfigurableBundleTemplateSlotCreateFormDataProvider
     */
    public function createConfigurableBundleTemplateSlotCreateFormDataProvider(): ConfigurableBundleTemplateSlotCreateFormDataProvider
    {
        return new ConfigurableBundleTemplateSlotCreateFormDataProvider($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider\ConfigurableBundleTemplateSlotEditFormDataProvider
     */
    public function createConfigurableBundleTemplateSlotEditFormDataProvider(): ConfigurableBundleTemplateSlotEditFormDataProvider
    {
        return new ConfigurableBundleTemplateSlotEditFormDataProvider(
            $this->getConfigurableBundleFacade(),
            $this->getLocaleFacade(),
            $this->getGlossaryFacade(),
            $this->getConfigurableBundleTemplateSlotEditFormDataProviderExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Expander\ConfigurableBundleTemplateSlotEditFormExpanderInterface
     */
    public function createConfigurableBundleTemplateSlotEditFormExpander(): ConfigurableBundleTemplateSlotEditFormExpanderInterface
    {
        return new ConfigurableBundleTemplateSlotEditFormExpander($this->getConfigurableBundleTemplateSlotEditFormExpanderPlugins());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Expander\ProductListButtonsExpanderInterface
     */
    public function createProductListButtonsExpander(): ProductListButtonsExpanderInterface
    {
        return new ProductListButtonsExpander();
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateTable
     */
    public function createConfigurableBundleTemplateTable(): ConfigurableBundleTemplateTable
    {
        return new ConfigurableBundleTemplateTable(
            $this->getConfigurableBundleTemplatePropelQuery(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateSlotTable
     */
    public function createConfigurableBundleTemplateSlotTable(int $idConfigurableBundleTemplate): ConfigurableBundleTemplateSlotTable
    {
        return new ConfigurableBundleTemplateSlotTable(
            $idConfigurableBundleTemplate,
            $this->getConfigurableBundleTemplateSlotPropelQuery(),
            $this->getLocaleFacade(),
            $this->getProductListFacade()
        );
    }

    /**
     * @param int $idConfigurableBundleTemplateSlot
     *
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateSlotProductsTable
     */
    public function createConfigurableBundleTemplateSlotProductsTable(int $idConfigurableBundleTemplateSlot): ConfigurableBundleTemplateSlotProductsTable
    {
        return new ConfigurableBundleTemplateSlotProductsTable(
            $idConfigurableBundleTemplateSlot,
            $this->getConfigurableBundleTemplateSlotPropelQuery(),
            $this->getProductPropelQuery(),
            $this->getLocaleFacade(),
            $this->getProductListFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateCreateTabs
     */
    public function createConfigurableBundleTemplateCreateTabs(): ConfigurableBundleTemplateCreateTabs
    {
        return new ConfigurableBundleTemplateCreateTabs();
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateEditTabs
     */
    public function createConfigurableBundleTemplateEditTabs(): ConfigurableBundleTemplateEditTabs
    {
        return new ConfigurableBundleTemplateEditTabs();
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Expander\ProductListUsedByTableDataExpanderInterface
     */
    public function createProductListUsedByTableDataExpander(): ProductListUsedByTableDataExpanderInterface
    {
        return new ProductListUsedByTableDataExpander(
            $this->getConfigurableBundleFacade(),
            $this->createProductListUsedByTableDataMapper()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Mapper\ProductListUsedByTableDataMapperInterface
     */
    public function createProductListUsedByTableDataMapper(): ProductListUsedByTableDataMapperInterface
    {
        return new ProductListUsedByTableDataMapper();
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateSlotCreateTabs
     */
    public function createConfigurableBundleTemplateSlotCreateTabs(): ConfigurableBundleTemplateSlotCreateTabs
    {
        return new ConfigurableBundleTemplateSlotCreateTabs();
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Handler\ConfigurableBundleTemplateSlotEditFormFileUploadHandlerInterface
     */
    public function createConfigurableBundleTemplateSlotEditFormFileUploadHandler(): ConfigurableBundleTemplateSlotEditFormFileUploadHandlerInterface
    {
        return new ConfigurableBundleTemplateSlotEditFormFileUploadHandler($this->getConfigurableBundleTemplateSlotEditFormFileUploadHandlerPlugins());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateSlotEditTabs
     */
    public function createConfigurableBundleTemplateSlotEditTabs(): ConfigurableBundleTemplateSlotEditTabs
    {
        return new ConfigurableBundleTemplateSlotEditTabs($this->getConfigurableBundleTemplateSlotEditTabsExpanderPlugins());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Provider\ProductConcreteRelationSubTabsProviderInterface
     */
    public function createProductConcreteRelationSubTabsProvider(): ProductConcreteRelationSubTabsProviderInterface
    {
        return new ProductConcreteRelationSubTabsProvider($this->getConfigurableBundleTemplateSlotEditSubTabsProviderPlugins());
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Provider\ProductConcreteRelationTablesProviderInterface
     */
    public function createProductConcreteRelationTablesProvider(): ProductConcreteRelationTablesProviderInterface
    {
        return new ProductConcreteRelationTablesProvider($this->getConfigurableBundleTemplateSlotEditTablesProviderPlugins());
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    public function getConfigurableBundleTemplatePropelQuery(): SpyConfigurableBundleTemplateQuery
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE);
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    public function getConfigurableBundleTemplateSlotPropelQuery(): SpyConfigurableBundleTemplateSlotQuery
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductPropelQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface
     */
    public function getConfigurableBundleFacade(): ConfigurableBundleGuiToConfigurableBundleFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::FACADE_CONFIGURABLE_BUNDLE);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ConfigurableBundleGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): ConfigurableBundleGuiToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToProductListFacadeInterface
     */
    public function getProductListFacade(): ConfigurableBundleGuiToProductListFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::FACADE_PRODUCT_LIST);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditTabsExpanderPluginInterface[]
     */
    public function getConfigurableBundleTemplateSlotEditTabsExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_TABS_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormExpanderPluginInterface[]
     */
    public function getConfigurableBundleTemplateSlotEditFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_FORM_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormDataProviderExpanderPluginInterface[]
     */
    public function getConfigurableBundleTemplateSlotEditFormDataProviderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_FORM_DATA_PROVIDER_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormFileUploadHandlerPluginInterface[]
     */
    public function getConfigurableBundleTemplateSlotEditFormFileUploadHandlerPlugins(): array
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_FORM_FILE_UPLOAD_HANDLER);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditSubTabsProviderPluginInterface[]
     */
    public function getConfigurableBundleTemplateSlotEditSubTabsProviderPlugins(): array
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_SUB_TABS_PROVIDER);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditTablesProviderPluginInterface[]
     */
    public function getConfigurableBundleTemplateSlotEditTablesProviderPlugins(): array
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_TABLES_PROVIDER);
    }
}
