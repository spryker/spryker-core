<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Tabs\TabsInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductListGui\Communication\Exporter\ProductListExporter;
use Spryker\Zed\ProductListGui\Communication\Exporter\ProductListExporterInterface;
use Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListAggregateFormDataProvider;
use Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListCategoryRelationFormDataProvider;
use Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListFormDataProvider;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListAggregateFormType;
use Spryker\Zed\ProductListGui\Communication\Importer\ProductListImporter;
use Spryker\Zed\ProductListGui\Communication\Importer\ProductListImporterInterface;
use Spryker\Zed\ProductListGui\Communication\Table\AssignedProductConcreteTable;
use Spryker\Zed\ProductListGui\Communication\Table\AvailableProductConcreteTable;
use Spryker\Zed\ProductListGui\Communication\Table\PluginExecutor\ProductListTablePluginExecutor;
use Spryker\Zed\ProductListGui\Communication\Table\PluginExecutor\ProductListTablePluginExecutorInterface;
use Spryker\Zed\ProductListGui\Communication\Table\ProductListTable;
use Spryker\Zed\ProductListGui\Communication\Tabs\AssignedProductConcreteRelationTabs;
use Spryker\Zed\ProductListGui\Communication\Tabs\AvailableProductConcreteRelationTabs;
use Spryker\Zed\ProductListGui\Communication\Tabs\ProductListAggregationTabs;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface;
use Spryker\Zed\ProductListGui\Dependency\Service\ProductListGuiToUtilCsvServiceInterface;
use Spryker\Zed\ProductListGui\ProductListGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 * @method \Spryker\Zed\ProductListGui\Persistence\ProductListGuiRepositoryInterface getRepository()()
 */
class ProductListGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createProductListTable(): AbstractTable
    {
        return new ProductListTable(
            $this->getProductListPropelQuery(),
            $this->createProductListTablePluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Table\AvailableProductConcreteTable
     */
    public function createAvailableProductConcreteTable()
    {
        return new AvailableProductConcreteTable($this->getLocaleFacade(), $this->getProductPropelQuery());
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Table\AssignedProductConcreteTable
     */
    public function createAssignedProductConcreteTable()
    {
        return new AssignedProductConcreteTable($this->getLocaleFacade(), $this->getProductPropelQuery());
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Table\PluginExecutor\ProductListTablePluginExecutorInterface
     */
    public function createProductListTablePluginExecutor(): ProductListTablePluginExecutorInterface
    {
        return new ProductListTablePluginExecutor(
            $this->getProductListTableActionExpanderPlugins(),
            $this->getProductListTableConfigExpanderPlugins(),
            $this->getProductListTableDataExpanderPlugins(),
            $this->getProductListTableHeaderExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Tabs\ProductListAggregationTabs
     */
    public function createProductListAggregationTabs(): TabsInterface
    {
        return new ProductListAggregationTabs();
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createAvailableProductConcreteRelationTabs(): TabsInterface
    {
        return new AvailableProductConcreteRelationTabs();
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createAssignedProductConcreteRelationTabs(): TabsInterface
    {
        return new AssignedProductConcreteRelationTabs();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListAggregateFormTransfer|null $productListAggregateFormTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductListAggregateForm(
        ?ProductListAggregateFormTransfer $productListAggregateFormTransfer = null,
        array $options = []
    ): FormInterface {
        return $this->getFormFactory()
            ->create(
                ProductListAggregateFormType::class,
                $productListAggregateFormTransfer,
                $options
            );
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListFormDataProvider
     */
    public function createProductListFormDataProvider()
    {
        return new ProductListFormDataProvider($this->getProductListFacade());
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListCategoryRelationFormDataProvider
     */
    public function createProductListCategoryRelationFormDataProvider()
    {
        return new ProductListCategoryRelationFormDataProvider(
            $this->getProductListFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Form\DataProvider\ProductListAggregateFormDataProvider
     */
    public function createProductListAggregateFormDataProvider()
    {
        return new ProductListAggregateFormDataProvider(
            $this->getRepository(),
            $this->createProductListFormDataProvider(),
            $this->createProductListCategoryRelationFormDataProvider(),
            $this->getProductListOwnerTypeFormExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductListGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Exporter\ProductListExporterInterface
     */
    public function createProductListExporter(): ProductListExporterInterface
    {
        return new ProductListExporter(
            $this->getUtilCsvService(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Communication\Importer\ProductListImporterInterface
     */
    public function createProductListImporter(): ProductListImporterInterface
    {
        return new ProductListImporter(
            $this->getUtilCsvService(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeInterface
     */
    public function getProductListFacade(): ProductListGuiToProductListFacadeInterface
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::FACADE_PRODUCT_LIST);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductPropelQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    public function getProductListPropelQuery(): SpyProductListQuery
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::PROPEL_QUERY_PRODUCT_LIST);
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListOwnerTypeFormExpanderPluginInterface[]
     */
    public function getProductListOwnerTypeFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::PLUGINS_PRODUCT_LIST_OWNER_TYPE_FORM_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableActionExpanderInterface[]
     */
    public function getProductListTableActionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::PLUGINS_PRODUCT_LIST_TABLE_ACTION_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableConfigExpanderPluginInterface[]
     */
    public function getProductListTableConfigExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::PLUGINS_PRODUCT_LIST_TABLE_CONFIG_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableDataExpanderPluginInterface[]
     */
    public function getProductListTableDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::PLUGINS_PRODUCT_LIST_TABLE_DATA_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableHeaderExpanderPluginInterface[]
     */
    public function getProductListTableHeaderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::PLUGINS_PRODUCT_LIST_TABLE_HEADER_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductListGui\Dependency\Service\ProductListGuiToUtilCsvServiceInterface
     */
    public function getUtilCsvService(): ProductListGuiToUtilCsvServiceInterface
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::SERVICE_UTIL_CSV);
    }
}
