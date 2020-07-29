<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication;

use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\MerchantGui\Communication\Expander\MerchantListDataExpander;
use Spryker\Zed\MerchantGui\Communication\Expander\MerchantListDataExpanderInterface;
use Spryker\Zed\MerchantGui\Communication\Form\Constraint\UniqueUrl;
use Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantFormDataProvider;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantCreateForm;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantStatusForm;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantUpdateForm;
use Spryker\Zed\MerchantGui\Communication\Form\ToggleActiveMerchantForm;
use Spryker\Zed\MerchantGui\Communication\Table\MerchantTable;
use Spryker\Zed\MerchantGui\Communication\Tabs\MerchantFormTabs;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToLocaleFacadeInterface;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToUrlFacadeInterface;
use Spryker\Zed\MerchantGui\MerchantGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \Spryker\Zed\MerchantGui\MerchantGuiConfig getConfig()
 */
class MerchantGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Table\MerchantTable
     */
    public function createMerchantTable(): MerchantTable
    {
        return new MerchantTable(
            $this->getMerchantPropelQuery(),
            $this->getMerchantFacade(),
            $this->getMerchantTableActionExpanderPlugins(),
            $this->getMerchantTableHeaderExpanderPlugins(),
            $this->getMerchantTableDataExpanderPlugins(),
            $this->getMerchantTableConfigExpanderPlugins()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantCreateForm(?MerchantTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantCreateForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantUpdateForm(?MerchantTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MerchantUpdateForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Form\DataProvider\MerchantFormDataProvider
     */
    public function createMerchantFormDataProvider(): MerchantFormDataProvider
    {
        return new MerchantFormDataProvider(
            $this->getMerchantFacade(),
            $this->getConfig(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Tabs\MerchantFormTabs
     */
    public function createMerchantFormTabs(): MerchantFormTabs
    {
        return new MerchantFormTabs(
            $this->getMerchantFormTabsExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Form\Constraint\UniqueUrl
     */
    public function createUniqueUrlConstraint(): UniqueUrl
    {
        return new UniqueUrl([
            UniqueUrl::OPTION_URL_FACADE => $this->getUrlFacade(),
        ]);
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Communication\Expander\MerchantListDataExpanderInterface
     */
    public function createMerchantListDataExpander(): MerchantListDataExpanderInterface
    {
        return new MerchantListDataExpander($this->getMerchantFacade());
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createToggleActiveMerchantForm(): FormInterface
    {
        return $this->getFormFactory()->create(ToggleActiveMerchantForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createMerchantStatusForm(): FormInterface
    {
        return $this->getFormFactory()->create(MerchantStatusForm::class);
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantGuiToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    public function getMerchantPropelQuery(): SpyMerchantQuery
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PROPEL_MERCHANT_QUERY);
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormExpanderPluginInterface[]
     */
    public function getMerchantFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PLUGINS_MERCHANT_FORM_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantUpdateFormViewExpanderPluginInterface[]
     */
    public function getMerchantUpdateFormViewExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PLUGINS_MERCHANT_UPDATE_FORM_VIEW_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableDataExpanderPluginInterface[]
     */
    public function getMerchantTableDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PLUGINS_MERCHANT_TABLE_DATA_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableActionExpanderPluginInterface[]
     */
    public function getMerchantTableActionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PLUGINS_MERCHANT_TABLE_ACTION_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableHeaderExpanderPluginInterface[]
     */
    public function getMerchantTableHeaderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PLUGINS_MERCHANT_TABLE_HEADER_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableConfigExpanderPluginInterface[]
     */
    public function getMerchantTableConfigExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PLUGINS_MERCHANT_TABLE_CONFIG_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormTabExpanderPluginInterface[]
     */
    public function getMerchantFormTabsExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PLUGINS_MERCHANT_FORM_TABS_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToUrlFacadeInterface
     */
    public function getUrlFacade(): MerchantGuiToUrlFacadeInterface
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): MerchantGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): Request
    {
        return $this->getRequestStack()->getCurrentRequest();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(MerchantGuiDependencyProvider::SERVICE_REQUEST_STACK);
    }
}
