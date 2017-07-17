<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeKeyForm;
use Spryker\Zed\ProductAttributeGui\Communication\Form\AttributeKeyFormDataProvider;
use Spryker\Zed\ProductAttributeGui\Communication\Table\ProductAbstractTable;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAttributeGui\ProductAttributeGuiConfig getConfig()
 */
class ProductAttributeGuiCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\ProductAttributeGui\Communication\Table\ProductAbstractTable
     */
    public function createProductAbstractTable()
    {
        return new ProductAbstractTable(
            $this->getProductQueryContainer(),
            $this->getLocaleFacade()->getCurrentLocale()
        );
    }

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
    public function createAttributeKeyForm(array $formData, array $formOptions = [])
    {
        return $this->getFormFactory()->create(AttributeKeyForm::class, $formData, $formOptions);
    }

}
