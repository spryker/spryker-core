<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductCategoryGui\Communication\DataProvider\ProductCategorySlotBlockDataProvider;
use Spryker\Zed\ProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm;
use Spryker\Zed\ProductCategoryGui\Communication\Form\Validator\Constraints\ProductCategoryConditionsConstraint;
use Spryker\Zed\ProductCategoryGui\Communication\Formatter\ProductLabelFormatter;
use Spryker\Zed\ProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface;
use Spryker\Zed\ProductCategoryGui\Dependency\Facade\ProductCategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductCategoryGui\Dependency\Facade\ProductCategoryGuiToProductFacadeInterface;
use Spryker\Zed\ProductCategoryGui\Dependency\QueryContainer\ProductCategoryGuiToCategoryQueryContainerInterface;
use Spryker\Zed\ProductCategoryGui\Dependency\QueryContainer\ProductCategoryGuiToProductQueryContainerInterface;
use Spryker\Zed\ProductCategoryGui\ProductCategoryGuiDependencyProvider;

class ProductCategoryGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm
     */
    public function createProductCategorySlotBlockConditionForm(): ProductCategorySlotBlockConditionForm
    {
        return new ProductCategorySlotBlockConditionForm();
    }

    /**
     * @return \Spryker\Zed\ProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface
     */
    public function createProductLabelFormatter(): ProductLabelFormatterInterface
    {
        return new ProductLabelFormatter();
    }

    /**
     * @return \Spryker\Zed\ProductCategoryGui\Communication\DataProvider\ProductCategorySlotBlockDataProvider
     */
    public function createProductCategorySlotBlockDataProvider(): ProductCategorySlotBlockDataProvider
    {
        return new ProductCategorySlotBlockDataProvider(
            $this->getProductQueryContainer(),
            $this->createProductLabelFormatter(),
            $this->getCategoryQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryGui\Communication\Form\Validator\Constraints\ProductCategoryConditionsConstraint
     */
    public function createProductCategoryConditionsConstraint(): ProductCategoryConditionsConstraint
    {
        return new ProductCategoryConditionsConstraint();
    }

    /**
     * @return \Spryker\Zed\ProductCategoryGui\Dependency\QueryContainer\ProductCategoryGuiToProductQueryContainerInterface
     */
    public function getProductQueryContainer(): ProductCategoryGuiToProductQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductCategoryGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryGui\Dependency\QueryContainer\ProductCategoryGuiToCategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer(): ProductCategoryGuiToCategoryQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductCategoryGuiDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryGui\Dependency\Facade\ProductCategoryGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductCategoryGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductCategoryGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductCategoryGui\Dependency\Facade\ProductCategoryGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductCategoryGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductCategoryGuiDependencyProvider::FACADE_LOCALE);
    }
}
