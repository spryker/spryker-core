<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication;

use Spryker\Shared\ProductManagement\Code\KeyBuilder\AttributeGlossaryKeyBuilder;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductManagement\Communication\Form\Attribute\AttributeTranslationCollectionForm;
use Spryker\Zed\ProductManagement\Communication\Form\AttributeForm;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\AttributeFormDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductConcreteFormEditDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductFormAddDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductFormEditDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\ProductConcreteFormEdit;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormEdit;
use Spryker\Zed\ProductManagement\Communication\Table\AttributeTable;
use Spryker\Zed\ProductManagement\Communication\Table\BundledProductTable;
use Spryker\Zed\ProductManagement\Communication\Table\ProductGroupTable;
use Spryker\Zed\ProductManagement\Communication\Table\ProductTable;
use Spryker\Zed\ProductManagement\Communication\Table\VariantTable;
use Spryker\Zed\ProductManagement\Communication\Tabs\ProductConcreteFormEditTabs;
use Spryker\Zed\ProductManagement\Communication\Tabs\ProductFormAddTabs;
use Spryker\Zed\ProductManagement\Communication\Tabs\ProductFormEditTabs;
use Spryker\Zed\ProductManagement\Communication\Transfer\AttributeFormTransferMapper;
use Spryker\Zed\ProductManagement\Communication\Transfer\AttributeTranslationFormTransferMapper;
use Spryker\Zed\ProductManagement\Communication\Transfer\ProductFormTransferMapper;
use Spryker\Zed\ProductManagement\ProductManagementDependencyProvider;

/**
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
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
        $formType = new ProductFormAdd(
            $this->createLocaleProvider(),
            $this->getProductQueryContainer(),
            $this->getQueryContainer(),
            $this->getMoneyFacade(),
            $this->getUtilTextService(),
            $this->getCurrencyFacade()
        );

        return $this->getFormFactory()->create($formType, $formData, $formOptions);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductFormEdit(array $formData, array $formOptions = [])
    {
        $formType = new ProductFormEdit(
            $this->createLocaleProvider(),
            $this->getProductQueryContainer(),
            $this->getQueryContainer(),
            $this->getMoneyFacade(),
            $this->getUtilTextService(),
            $this->getCurrencyFacade()
        );

        return $this->getFormFactory()->create($formType, $formData, $formOptions);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductVariantFormEdit(array $formData, array $formOptions = [])
    {
        $formType = new ProductConcreteFormEdit(
            $this->createLocaleProvider(),
            $this->getProductQueryContainer(),
            $this->getQueryContainer(),
            $this->getMoneyFacade(),
            $this->getUtilTextService(),
            $this->getCurrencyFacade()
        );

        return $this->getFormFactory()->create($formType, $formData, $formOptions);
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
            $this->getPriceFacade(),
            $this->getProductFacade(),
            $this->getProductImageFacade(),
            $this->createLocaleProvider(),
            $currentLocale,
            $this->getProductAttributeCollection(),
            $this->getProductTaxCollection(),
            $this->getConfig()->getImageUrlPrefix(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductFormEditDataProvider
     */
    public function createProductFormEditDataProvider()
    {
        $currentLocale = $this->getLocaleFacade()->getCurrentLocale();

        return new ProductFormEditDataProvider(
            $this->getCategoryQueryContainer(),
            $this->getQueryContainer(),
            $this->getProductQueryContainer(),
            $this->getStockQueryContainer(),
            $this->getPriceFacade(),
            $this->getProductFacade(),
            $this->getProductImageFacade(),
            $this->createLocaleProvider(),
            $currentLocale,
            $this->getProductAttributeCollection(),
            $this->getProductTaxCollection(),
            $this->getConfig()->getImageUrlPrefix(),
            $this->getStore()
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
            $this->getPriceFacade(),
            $this->getProductFacade(),
            $this->getProductImageFacade(),
            $this->createLocaleProvider(),
            $currentLocale,
            $this->getProductAttributeCollection(),
            $this->getProductTaxCollection(),
            $this->getConfig()->getImageUrlPrefix(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Plugin\ProductAbstractViewPluginInterface[]
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
     * @return \Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilTextInterface
     */
    public function getUtilTextService()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface
     */
    public function getPriceFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT);
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
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection()
    {
        return $this->reindexAttributeCollection(
            $this->getFacade()->getProductAttributeCollection()
        );
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
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $attributeCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected function reindexAttributeCollection(array $attributeCollection)
    {
        $result = [];
        foreach ($attributeCollection as $attributeTransfer) {
            $result[$attributeTransfer->getKey()] = $attributeTransfer;
        }

        return $result;
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createAttributeTable()
    {
        return new AttributeTable($this->getQueryContainer());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAttributeForm(array $data = [], array $options = [])
    {
        $attributeFormType = $this->createAttributeFormType();

        return $this->getFormFactory()->create($attributeFormType, $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\AbstractType
     */
    protected function createAttributeFormType()
    {
        return new AttributeForm($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\AttributeFormDataProvider
     */
    public function createAttributeFormDataProvider()
    {
        return new AttributeFormDataProvider($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAttributeTranslationFormCollection(array $data = [], array $options = [])
    {
        $attributeTranslationFormCollectionType = $this->createAttributeTranslationFormCollectionType();

        return $this->getFormFactory()->create($attributeTranslationFormCollectionType, $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\AbstractType
     */
    public function createAttributeTranslationFormCollectionType()
    {
        return new AttributeTranslationCollectionForm();
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider
     */
    public function createAttributeTranslationFormCollectionDataProvider()
    {
        return new AttributeTranslationFormCollectionDataProvider(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->getGlossaryFacade(),
            $this->createAttributeGlossaryKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Transfer\AttributeFormTransferMapperInterface
     */
    public function createAttributeFormTransferGenerator()
    {
        return new AttributeFormTransferMapper();
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Transfer\AttributeTranslationFormTransferMapperInterface
     */
    public function createAttributeTranslationFormTransferGenerator()
    {
        return new AttributeTranslationFormTransferMapper();
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
            $this->getUtilTextService(),
            $this->createLocaleProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\LocaleProvider
     */
    public function createLocaleProvider()
    {
        return new LocaleProvider(
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createProductTable()
    {
        return new ProductTable($this->getProductQueryContainer(), $this->getLocaleFacade()->getCurrentLocale());
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
            $type
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
            $this->getPriceFacade(),
            $this->getMoneyFacade(),
            $this->getAvailabilityFacade(),
            $this->getLocaleFacade()->getCurrentLocale(),
            $idProductConcrete
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
            $idProductAbstract
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductAttributeInterface
     */
    public function getProductAttributeFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }

    /**
     * @return \Spryker\Shared\ProductManagement\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected function createAttributeGlossaryKeyBuilder()
    {
        return new AttributeGlossaryKeyBuilder();
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
        return new ProductFormEditTabs();
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface
     */
    public function createProductConcreteFormEditTabs()
    {
        return new ProductConcreteFormEditTabs();
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToCurrencyInterface
     */
    protected function getCurrencyFacade()
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
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreInterface
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::STORE);
    }

}
