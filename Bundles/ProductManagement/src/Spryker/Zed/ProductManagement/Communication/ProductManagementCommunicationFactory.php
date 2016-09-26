<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication;

use Spryker\Shared\ProductManagement\Code\KeyBuilder\AttributeGlossaryKeyBuilder;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductManagement\Communication\Form\AttributeForm;
use Spryker\Zed\ProductManagement\Communication\Form\Attribute\AttributeTranslationCollectionForm;
use Spryker\Zed\ProductManagement\Communication\Form\Attribute\ReadOnlyAttributeForm;
use Spryker\Zed\ProductManagement\Communication\Form\Attribute\ReadOnlyAttributeTranslationCollectionForm;
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
use Spryker\Zed\ProductManagement\Communication\Table\ProductTable;
use Spryker\Zed\ProductManagement\Communication\Table\VariantTable;
use Spryker\Zed\ProductManagement\Communication\Transfer\AttributeFormTransferGenerator;
use Spryker\Zed\ProductManagement\Communication\Transfer\AttributeTranslationFormTransferGenerator;
use Spryker\Zed\ProductManagement\Communication\Transfer\ProductFormTransferGenerator;
use Spryker\Zed\ProductManagement\ProductManagementDependencyProvider;
use Spryker\Zed\Product\Business\Product\VariantGenerator;

/**
 * @TODO Move attribute and form methods into separate factories
 *
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
            $this->getQueryContainer()
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
            $this->getQueryContainer()
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
            $this->getQueryContainer()
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
            $this->getConfig()->getImageUrlPrefix()
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
            $this->getConfig()->getImageUrlPrefix()
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
            $this->getConfig()->getImageUrlPrefix()
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
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_LOCALE);
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
        $taxSet = $taxSet->getTaxSets();

        $result = [];
        foreach ($taxSet as $tax) {
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
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createReadOnlyAttributeForm(array $data = [], array $options = [])
    {
        $readOnlyAttributeFormType = $this->createReadOnlyAttributeFormType();

        return $this->getFormFactory()->create($readOnlyAttributeFormType, $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\AbstractType
     */
    protected function createAttributeFormType()
    {
        return new AttributeForm($this->getQueryContainer());
    }

    /**
     * @return \Symfony\Component\Form\AbstractType
     */
    protected function createReadOnlyAttributeFormType()
    {
        return new ReadOnlyAttributeForm($this->getQueryContainer());
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
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createReadOnlyAttributeTranslationFormCollection(array $data = [], array $options = [])
    {
        $attributeTranslationFormCollectionType = $this->createReadOnlyAttributeTranslationFormCollectionType();

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
     * @return \Symfony\Component\Form\AbstractType
     */
    public function createReadOnlyAttributeTranslationFormCollectionType()
    {
        return new ReadOnlyAttributeTranslationCollectionForm();
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
     * @return \Spryker\Zed\ProductManagement\Communication\Transfer\AttributeFormTransferGeneratorInterface
     */
    public function createAttributeFormTransferGenerator()
    {
        return new AttributeFormTransferGenerator();
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Transfer\AttributeTranslationFormTransferGeneratorInterface
     */
    public function createAttributeTranslationFormTransferGenerator()
    {
        return new AttributeTranslationFormTransferGenerator();
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Transfer\ProductFormTransferGenerator
     */
    public function createProductFormTransferGenerator()
    {
        return new ProductFormTransferGenerator(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
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
     * @return \Spryker\Zed\Product\Business\Product\VariantGeneratorInterface
     */
    public function createProductVariantGenerator()
    {
        return new VariantGenerator();
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createProductTable()
    {
        return new ProductTable($this->getProductQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createVariantTable($idProductAbstract)
    {
        return new VariantTable($this->getProductQueryContainer(), $idProductAbstract);
    }

    /**
     * @return \Spryker\Shared\ProductManagement\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected function createAttributeGlossaryKeyBuilder()
    {
        return new AttributeGlossaryKeyBuilder();
    }

}
