<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication;

use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\Price\ProductMoneyCollectionDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductConcreteFormAddDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductConcreteFormEditDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductFormAddDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductFormEditDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\TableFilterFormDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormEdit;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormEdit;
use Spryker\Zed\ProductManagement\Communication\Form\TableFilterForm;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductAttributeHelper;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductAttributeHelperInterface;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductConcreteSuperAttributeFilterHelper;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductConcreteSuperAttributeFilterHelperInterface;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductStockHelper;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductTypeHelper;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductValidity\ProductValidityActivityMessenger;
use Spryker\Zed\ProductManagement\Communication\PluginExecutor\AbstractProductEditEditViewExpanderPluginExecutor;
use Spryker\Zed\ProductManagement\Communication\PluginExecutor\AbstractProductEditViewExpanderPluginExecutorInterface;
use Spryker\Zed\ProductManagement\Communication\PluginExecutor\ProductConcreteEditEditViewExpanderPluginExecutor;
use Spryker\Zed\ProductManagement\Communication\PluginExecutor\ProductConcreteEditViewExpanderPluginExecutorInterface;
use Spryker\Zed\ProductManagement\Communication\Reader\ProductAttributeReader;
use Spryker\Zed\ProductManagement\Communication\Reader\ProductAttributeReaderInterface;
use Spryker\Zed\ProductManagement\Communication\Table\BundledProductTable;
use Spryker\Zed\ProductManagement\Communication\Table\ProductGroupTable;
use Spryker\Zed\ProductManagement\Communication\Table\ProductTable;
use Spryker\Zed\ProductManagement\Communication\Table\VariantTable;
use Spryker\Zed\ProductManagement\Communication\Tabs\ProductConcreteFormAddTabs;
use Spryker\Zed\ProductManagement\Communication\Tabs\ProductConcreteFormEditTabs;
use Spryker\Zed\ProductManagement\Communication\Tabs\ProductFormAddTabs;
use Spryker\Zed\ProductManagement\Communication\Tabs\ProductFormEditTabs;
use Spryker\Zed\ProductManagement\Communication\Transfer\ProductFormTransferMapper;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductBundleInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductCategoryInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreFacadeInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTranslatorFacadeInterface;
use Spryker\Zed\ProductManagement\ProductManagementDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface getRepository()()
 */
class ProductManagementCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductFormAdd(array $formData, array $formOptions = [])
    {
        return $this->getFormFactory()->create(ProductFormAdd::class, $formData, $formOptions);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductFormEdit(array $formData, array $formOptions = [])
    {
        return $this->getFormFactory()->create(ProductFormEdit::class, $formData, $formOptions);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductVariantFormAdd(array $formData, array $formOptions = [])
    {
        return $this->getFormFactory()->create(ProductConcreteFormAdd::class, $formData, $formOptions);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductVariantFormEdit(array $formData, array $formOptions = [])
    {
        return $this->getFormFactory()->create(ProductConcreteFormEdit::class, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductFormAddDataProvider
     */
    public function createProductFormAddDataProvider()
    {
        $currentLocale = $this->getLocaleFacade()->getCurrentLocale();

        return new ProductFormAddDataProvider(
            $this->getCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->getProductQueryContainer(),
            $this->getStockQueryContainer(),
            $this->getProductFacade(),
            $this->getProductImageFacade(),
            $this->getPriceProductFacade(),
            $this->createLocaleProvider(),
            $currentLocale,
            $this->getProductTaxCollection(),
            $this->getConfig()->getImageUrlPrefix(),
            $this->createProductAttributeReader(),
            $this->getProductAbstractFormDataProviderExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductFormEditDataProvider
     */
    public function createProductFormEditDataProvider()
    {
        return new ProductFormEditDataProvider(
            $this->getCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->getProductQueryContainer(),
            $this->getStockQueryContainer(),
            $this->getProductFacade(),
            $this->getProductImageFacade(),
            $this->getPriceProductFacade(),
            $this->createLocaleProvider(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $this->getProductTaxCollection(),
            $this->getConfig()->getImageUrlPrefix(),
            $this->getProductAbstractFormDataProviderExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductConcreteFormAddDataProvider
     */
    public function createProductVariantFormAddDataProvider()
    {
        $currentLocale = $this->getLocaleFacade()->getCurrentLocale();

        return new ProductConcreteFormAddDataProvider(
            $this->getStockQueryContainer(),
            $this->getProductFacade(),
            $this->createLocaleProvider(),
            $currentLocale,
            $this->getProductTaxCollection(),
            $this->getProductAttributeFacade(),
            $this->createProductAttributeReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductConcreteFormEditDataProvider
     */
    public function createProductVariantFormEditDataProvider()
    {
        $currentLocale = $this->getLocaleFacade()->getCurrentLocale();

        return new ProductConcreteFormEditDataProvider(
            $this->getCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->getProductQueryContainer(),
            $this->getStockQueryContainer(),
            $this->getProductFacade(),
            $this->getProductImageFacade(),
            $this->getPriceProductFacade(),
            $this->createLocaleProvider(),
            $currentLocale,
            $this->getProductTaxCollection(),
            $this->getConfig()->getImageUrlPrefix(),
            $this->createProductStockHelper(),
            $this->getProductConcreteFormEditDataProviderExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\Price\ProductMoneyCollectionDataProvider
     */
    public function createMoneyCollectionMultiStoreDataProvider()
    {
        return new ProductMoneyCollectionDataProvider($this->getCurrencyFacade(), $this->getPriceProductFacade());
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormDataProviderExpanderPluginInterface>
     */
    public function getProductAbstractFormDataProviderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_FORM_DATA_PROVIDER_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagement\Communication\Plugin\ProductAbstractViewPluginInterface>
     */
    public function getProductAbstractViewPlugins()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_VIEW);
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    public function getStockQueryContainer()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::QUERY_CONTAINER_STOCK);
    }

    /**
     * @return \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    public function getProductImageQueryContainer()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::QUERY_CONTAINER_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface
     */
    public function getProductGroupQueryContainer()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::QUERY_CONTAINER_PRODUCT_GROUP);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface
     */
    public function getPriceProductFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductBundleInterface
     */
    public function getProductBundleFacade(): ProductManagementToProductBundleInterface
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT_BUNDLE);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface
     */
    public function getProductImageFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Reader\ProductAttributeReaderInterface
     */
    public function createProductAttributeReader(): ProductAttributeReaderInterface
    {
        return new ProductAttributeReader($this->getProductAttributeFacade());
    }

    /**
     * @return array
     */
    public function getProductTaxCollection()
    {
        $taxSet = $this->getTaxFacade()->getTaxSets();

        $result = [];
        foreach ($taxSet->getTaxSets() as $tax) {
            $result[$tax->getIdTaxSet()] = $tax->getName();
        }

        return $result;
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Helper\ProductValidity\ProductValidityActivityMessengerInterface
     */
    public function createProductValidityActivityMessenger()
    {
        return new ProductValidityActivityMessenger(
            $this->getConfig(),
            $this->getProductFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Transfer\ProductFormTransferMapper
     */
    public function createProductFormTransferGenerator()
    {
        return new ProductFormTransferMapper(
            $this->getProductQueryContainer(),
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->createLocaleProvider(),
            $this->getProductFormTransferMapperExpanderPlugins(),
            $this->getProductAbstractTransferMapperPlugins(),
            $this->createProductConcreteSuperAttributeFilterHelper(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider
     */
    public function createLocaleProvider()
    {
        return new LocaleProvider(
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Table\ProductTable
     */
    public function createProductTable()
    {
        return new ProductTable(
            $this->getProductQueryContainer(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $this->createProductTypeHelper(),
            $this->getRepository(),
            $this->getProductFacade(),
            $this->getProductTableDataExpanderPlugins(),
            $this->getProductTableConfigurationExpanderPlugins(),
            $this->getProductTableDataBulkExpanderPlugins(),
            $this->getProductTableActionExpanderPlugins(),
            $this->getConfig(),
        );
    }

    /**
     * @param int $idProductAbstract
     * @param string $type
     *
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createVariantTable($idProductAbstract, $type)
    {
        return new VariantTable(
            $this->getProductQueryContainer(),
            $idProductAbstract,
            $this->getLocaleFacade()->getCurrentLocale(),
            $type,
            $this->getProductVariantTableActionExpanderPlugins(),
        );
    }

    /**
     * @param int|null $idProductConcrete
     *
     * @return \Spryker\Zed\ProductManagement\Communication\Table\BundledProductTable
     */
    public function createBundledProductTable($idProductConcrete = null)
    {
        return new BundledProductTable(
            $this->getProductQueryContainer(),
            $this->getUtilEncoding(),
            $this->getPriceProductFacade(),
            $this->getMoneyFacade(),
            $this->getAvailabilityFacade(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $this->getPriceFacade(),
            $this->getStoreFacade(),
            $idProductConcrete,
        );
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createProductGroupTable($idProductAbstract)
    {
        return new ProductGroupTable(
            $this->getProductQueryContainer(),
            $this->getProductGroupQueryContainer(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $idProductAbstract,
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Helper\ProductStockHelperInterface
     */
    public function createProductStockHelper()
    {
        return new ProductStockHelper();
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeInterface
     */
    public function getProductAttributeFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createProductFormAddTabs()
    {
        return new ProductFormAddTabs();
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createProductFormEditTabs()
    {
        return new ProductFormEditTabs($this->getProductAbstractFormEditTabsExpanderPlugins());
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createProductConcreteFormAddTabs()
    {
        return new ProductConcreteFormAddTabs();
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createProductConcreteFormEditTabs()
    {
        return new ProductConcreteFormEditTabs(
            $this->getProductConcreteFormEditTabsExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getStoreRelationFormTypePlugin()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Helper\ProductTypeHelperInterface
     */
    public function createProductTypeHelper()
    {
        return new ProductTypeHelper(
            $this->getProductQueryContainer(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface
     */
    public function getMoneyFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCurrencyInterface
     */
    public function getCurrencyFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityInterface
     */
    protected function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreFacadeInterface
     */
    protected function getStoreFacade(): ProductManagementToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): ProductManagementToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @deprecated Use {@link Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory::getProductTableDataBulkExpanderPlugins()} instead.
     *
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableDataExpanderPluginInterface>
     */
    protected function getProductTableDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_TABLE_DATA_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface
     */
    public function getPriceFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getMoneyFormTypePlugin()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGIN_MONEY_FORM_TYPE);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStockInterface
     */
    public function getStockFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteEditFormExpanderPluginInterface>
     */
    public function getProductConcreteEditFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PRODUCT_CONCRETE_EDIT_FORM_EXPANDER_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditDataProviderExpanderPluginInterface>
     */
    public function getProductConcreteFormEditDataProviderExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PRODUCT_CONCRETE_FORM_EDIT_DATA_PROVIDER_EXPANDER_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductFormTransferMapperExpanderPluginInterface>
     */
    public function getProductFormTransferMapperExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PRODUCT_FORM_TRANSFER_MAPPER_EXPANDER_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditTabsExpanderPluginInterface>
     */
    public function getProductConcreteFormEditTabsExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_CONCRETE_FORM_EDIT_TABS_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormEditTabsExpanderPluginInterface>
     */
    public function getProductAbstractFormEditTabsExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_FORM_EDIT_TABS_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormExpanderPluginInterface>
     */
    public function getProductAbstractFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_FORM_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormExpanderPluginInterface>
     */
    public function getProductConcreteFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_CONCRETE_FORM_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Helper\ProductConcreteSuperAttributeFilterHelperInterface
     */
    public function createProductConcreteSuperAttributeFilterHelper(): ProductConcreteSuperAttributeFilterHelperInterface
    {
        return new ProductConcreteSuperAttributeFilterHelper();
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Helper\ProductAttributeHelperInterface
     */
    public function createProductAttributeHelper(): ProductAttributeHelperInterface
    {
        return new ProductAttributeHelper(
            $this->getProductFacade(),
            $this->getProductQueryContainer(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductCategoryInterface
     */
    public function getProductCategoryFacade(): ProductManagementToProductCategoryInterface
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\PluginExecutor\AbstractProductEditViewExpanderPluginExecutorInterface
     */
    public function createAbstractProductEditViewExpanderPluginExecutor(): AbstractProductEditViewExpanderPluginExecutorInterface
    {
        return new AbstractProductEditEditViewExpanderPluginExecutor(
            $this->getAbstractProductEditViewExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\PluginExecutor\ProductConcreteEditViewExpanderPluginExecutorInterface
     */
    public function createProductConcreteEditViewExpanderPluginExecutor(): ProductConcreteEditViewExpanderPluginExecutorInterface
    {
        return new ProductConcreteEditEditViewExpanderPluginExecutor(
            $this->getProductConcreteEditViewExpanderPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractEditViewExpanderPluginInterface>
     */
    public function getAbstractProductEditViewExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_EDIT_VIEW_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteEditViewExpanderPluginInterface>
     */
    public function getProductConcreteEditViewExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_CONCRETE_EDIT_VIEW_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractListActionViewDataExpanderPluginInterface>
     */
    public function getProductAbstractListActionViewDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_LIST_ACTION_VIEW_DATA_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractViewActionViewDataExpanderPluginInterface>
     */
    public function getProductAbstractViewActionViewDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_VIEW_ACTION_VIEW_DATA_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableConfigurationExpanderPluginInterface>
     */
    public function getProductTableConfigurationExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_TABLE_CONFIGURATION_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableDataBulkExpanderPluginInterface>
     */
    public function getProductTableDataBulkExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_TABLE_DATA_BULK_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductTableActionExpanderPluginInterface>
     */
    public function getProductTableActionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_TABLE_ACTION_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductVariantTableActionExpanderPluginInterface>
     */
    public function getProductVariantTableActionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_VARIANT_TABLE_ACTION_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractTransferMapperPluginInterface>
     */
    public function getProductAbstractTransferMapperPlugins(): array
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_TRANSFER_MAPPER);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTableFilterForm(ProductTableCriteriaTransfer $productTableCriteriaTransfer, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(
            TableFilterForm::class,
            $productTableCriteriaTransfer,
            $options,
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\TableFilterFormDataProvider
     */
    public function createTableFilterFormDataProvider(): TableFilterFormDataProvider
    {
        return new TableFilterFormDataProvider(
            $this->getStoreFacade(),
        );
    }
}
