<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductFormAddDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductFormEditDataProvider;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormAdd;
use Spryker\Zed\ProductManagement\Communication\Form\ProductFormEdit;
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
        $formType = new ProductFormAdd();

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
        $formType = new ProductFormEdit();

        return $this->getFormFactory()->create($formType, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductFormAddDataProvider
     */
    public function createProductFormAddDataProvider()
    {
        return new ProductFormAddDataProvider(
            $this->getCategoryQueryContainer(),
            $this->getProductQueryContainer(),
            $this->getPriceFacade(),
            $this->getProductFacade(),
            $this->getProductManagementFacade(),
            $this->getLocaleFacade(),
            $this->getProductAttributeGroupCollection(),
            $this->getProductAttributeCollection(),
            $this->getProductTaxCollection()
        );
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\ProductFormEditDataProvider
     */
    public function createProductFormEditDataProvider()
    {
        return new ProductFormEditDataProvider(
            $this->getCategoryQueryContainer(),
            $this->getProductQueryContainer(),
            $this->getPriceFacade(),
            $this->getProductFacade(),
            $this->getProductManagementFacade(),
            $this->getLocaleFacade(),
            $this->getProductAttributeGroupCollection(),
            $this->getProductAttributeCollection(),
            $this->getProductTaxCollection()
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
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTaxInterface
     */
    public function getTaxFacade()
    {
        return $this->getProvidedDependency(ProductManagementDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface
     */
    public function getProductManagementFacade()
    {
        return $this->getFacade();
    }

    /**
     * @return array
     */
    public function getProductAttributeGroupCollection()
    {
        return [
            'size' => 'Size',
            'color' => 'Color',
            'flavour' => 'Flavour',
        ];
    }

    /**
     * @return array
     */
    public function getProductAttributeCollection()
    {
        return $this->getProductManagementFacade()->getProductAttributeCollection();
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

}
