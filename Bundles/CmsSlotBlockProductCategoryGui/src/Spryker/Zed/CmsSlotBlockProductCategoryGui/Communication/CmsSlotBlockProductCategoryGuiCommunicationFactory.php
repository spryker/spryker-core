<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication;

use Spryker\Zed\CmsSlotBlockProductCategoryGui\CmsSlotBlockProductCategoryGuiDependencyProvider;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\DataProvider\ProductCategorySlotBlockDataProvider;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\DataProvider\ProductCategorySlotBlockDataProviderInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\Validator\Constraints\ProductCategoryConditionConstraint;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Formatter\ProductLabelFormatter;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToProductFacadeInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToTranslatorFacadeInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\QueryContainer\CmsSlotBlockProductCategoryGuiToProductQueryContainerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CmsSlotBlockProductCategoryGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm
     */
    public function createProductCategorySlotBlockConditionForm(): ProductCategorySlotBlockConditionForm
    {
        return new ProductCategorySlotBlockConditionForm();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Formatter\ProductLabelFormatterInterface
     */
    public function createProductLabelFormatter(): ProductLabelFormatterInterface
    {
        return new ProductLabelFormatter();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\DataProvider\ProductCategorySlotBlockDataProviderInterface
     */
    public function createProductCategorySlotBlockDataProvider(): ProductCategorySlotBlockDataProviderInterface
    {
        return new ProductCategorySlotBlockDataProvider(
            $this->getProductQueryContainer(),
            $this->createProductLabelFormatter(),
            $this->getCategoryFacade(),
            $this->getLocaleFacade(),
            $this->getTranslatorFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\Validator\Constraints\ProductCategoryConditionConstraint
     */
    public function createProductCategoryConditionsConstraint(): ProductCategoryConditionConstraint
    {
        return new ProductCategoryConditionConstraint();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\QueryContainer\CmsSlotBlockProductCategoryGuiToProductQueryContainerInterface
     */
    public function getProductQueryContainer(): CmsSlotBlockProductCategoryGuiToProductQueryContainerInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockProductCategoryGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToCategoryFacadeInterface
     */
    public function getCategoryFacade(): CmsSlotBlockProductCategoryGuiToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockProductCategoryGuiDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToProductFacadeInterface
     */
    public function getProductFacade(): CmsSlotBlockProductCategoryGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockProductCategoryGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): CmsSlotBlockProductCategoryGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockProductCategoryGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): CmsSlotBlockProductCategoryGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockProductCategoryGuiDependencyProvider::FACADE_TRANSLATOR);
    }
}
