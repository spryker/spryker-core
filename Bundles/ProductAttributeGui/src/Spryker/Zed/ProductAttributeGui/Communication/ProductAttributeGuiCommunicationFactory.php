<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeKeyForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeKeyFormDataProvider;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeTranslationCollectionForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeFormDataProvider;
use Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider;
use Spryker\Zed\ProductAttributeGui\Communication\Table\AttributeTable;
use Spryker\Zed\ProductAttributeGui\Communication\Transfer\AttributeFormTransferMapper;
use Spryker\Zed\ProductAttributeGui\Communication\Transfer\AttributeTranslationFormTransferMapper;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class ProductAttributeGuiCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Dependency\Facade\ProductAttributeGuiToProductAttributeInterface
     */
    public function getProductAttributeFacade()
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeKeyFormDataProviderInterface
     */
    public function createAttributeKeyFormDataProvider()
    {
        return new AttributeKeyFormDataProvider();
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAttributeKeyForm(array $formData, array $formOptions = [])
    {
        return $this->getFormFactory()
            ->create(
                AttributeKeyForm::class,
                $formData,
                $formOptions
            );
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
     * @return \Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeFormDataProvider
     */
    public function createAttributeFormDataProvider()
    {
        return new AttributeFormDataProvider(
            $this->getProductAttributeQueryContainer(),
            $this->getProductAttributeFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Table\AbstractTable
     */
    public function createAttributeTable()
    {
        return new AttributeTable(
            $this->getProductAttributeQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Communication\Transfer\AttributeFormTransferMapperInterface
     */
    public function createAttributeFormTransferGenerator()
    {
        return new AttributeFormTransferMapper();
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider
     */
    public function createAttributeTranslationFormCollectionDataProvider()
    {
        return new AttributeTranslationFormCollectionDataProvider(
            $this->getProductAttributeFacade(),
            $this->getProductAttributeQueryContainer(),
            $this->getLocaleFacade()
        );
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
     * @return \Spryker\Zed\ProductAttributeGui\Communication\Transfer\AttributeTranslationFormTransferMapper
     */
    public function createAttributeTranslationFormTransferGenerator()
    {
        return new AttributeTranslationFormTransferMapper();
    }

    /**
     * @return \Symfony\Component\Form\AbstractType
     */
    protected function createAttributeFormType()
    {
        return new AttributeForm($this->getProductAttributeQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface
     */
    protected function getProductAttributeQueryContainer()
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_ATTRIBUTE);
    }

}
