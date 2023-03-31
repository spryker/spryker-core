<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StoreGui\Communication\Expander\StoreListDataExpander;
use Spryker\Zed\StoreGui\Communication\Expander\StoreListDataExpanderInterface;
use Spryker\Zed\StoreGui\Communication\Form\CreateStoreForm;
use Spryker\Zed\StoreGui\Communication\Form\DataProvider\StoreFormDataProvider;
use Spryker\Zed\StoreGui\Communication\Form\DataProvider\StoreRelationDropdownDataProvider;
use Spryker\Zed\StoreGui\Communication\Form\Transformer\IdStoresDataTransformer;
use Spryker\Zed\StoreGui\Communication\Form\UpdateStoreForm;
use Spryker\Zed\StoreGui\Communication\Table\StoreTable;
use Spryker\Zed\StoreGui\Communication\Tabs\StoreFormTabs;
use Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface;
use Spryker\Zed\StoreGui\Dependency\Service\StoreGuiToUtilEncodingServiceInterface;
use Spryker\Zed\StoreGui\StoreGuiDependencyProvider;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \Spryker\Zed\StoreGui\StoreGuiConfig getConfig()
 */
class StoreGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\StoreGui\Communication\Table\StoreTable
     */
    public function createStoreTable(): StoreTable
    {
        return new StoreTable(
            $this->getStorePropelQuery(),
            $this->getStoreTableExpanderPlugins(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\StoreGui\Communication\Form\DataProvider\StoreRelationDropdownDataProvider
     */
    public function createStoreRelationDropdownDataProvider(): StoreRelationDropdownDataProvider
    {
        return new StoreRelationDropdownDataProvider($this->getStoreFacade());
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createIdStoresDataTransformer(): DataTransformerInterface
    {
        return new IdStoresDataTransformer($this->getUtilEncodingService());
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::PROPEL_QUERY_STORE);
    }

    /**
     * @return \Spryker\Zed\StoreGui\Dependency\Service\StoreGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): StoreGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\StoreGui\Communication\Form\DataProvider\StoreFormDataProvider
     */
    public function createStoreFormDataProvider(): StoreFormDataProvider
    {
        return new StoreFormDataProvider($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\StoreGui\Communication\Tabs\StoreFormTabs
     */
    public function createStoreFormTabs(): StoreFormTabs
    {
        return new StoreFormTabs(
            $this->getStoreFormTabsExpanderPlugins(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCreateStoreForm(?StoreTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CreateStoreForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getUpdateStoreForm(?StoreTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(UpdateStoreForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): StoreGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormExpanderPluginInterface>
     */
    public function getStoreFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::PLUGINS_STORE_FORM_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormViewExpanderPluginInterface>
     */
    public function getStoreFormViewExpanderPlugins(): array
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::PLUGINS_STORE_FORM_VIEW_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormTabExpanderPluginInterface>
     */
    public function getStoreFormTabsExpanderPlugins(): array
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::PLUGINS_STORE_FORM_TABS_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreTableExpanderPluginInterface>
     */
    public function getStoreTableExpanderPlugins(): array
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::PLUGINS_STORE_TABLE_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreViewExpanderPluginInterface>
     */
    public function getStoreViewExpanderPlugins(): array
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::PLUGINS_STORE_VIEW_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\StoreGui\Communication\Expander\StoreListDataExpanderInterface
     */
    public function createStoreListDataExpander(): StoreListDataExpanderInterface
    {
        return new StoreListDataExpander($this->getStoreFacade(), $this->getRequest());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->getRequestStack()->getCurrentRequest();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::SERVICE_REQUEST_STACK);
    }
}
