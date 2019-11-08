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
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Category\CmsSlotBlockProductCategoryGuiCategoryReader;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Category\CmsSlotBlockProductCategoryGuiCategoryReaderInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Product\CmsSlotBlockProductCategoryGuiProductReader;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Product\CmsSlotBlockProductCategoryGuiProductReaderInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToTranslatorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence\CmsSlotBlockProductCategoryGuiRepositoryInterface getRepository()
 */
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
            $this->createCmsSlotBlockProductCategoryGuiProductReader(),
            $this->createCmsSlotBlockProductCategoryGuiCategoryReader(),
            $this->getTranslatorFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Product\CmsSlotBlockProductCategoryGuiProductReaderInterface
     */
    public function createCmsSlotBlockProductCategoryGuiProductReader(): CmsSlotBlockProductCategoryGuiProductReaderInterface
    {
        return new CmsSlotBlockProductCategoryGuiProductReader($this->getRepository(), $this->createProductLabelFormatter());
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Category\CmsSlotBlockProductCategoryGuiCategoryReaderInterface
     */
    public function createCmsSlotBlockProductCategoryGuiCategoryReader(): CmsSlotBlockProductCategoryGuiCategoryReaderInterface
    {
        return new CmsSlotBlockProductCategoryGuiCategoryReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\Validator\Constraints\ProductCategoryConditionConstraint
     */
    public function createProductCategoryConditionsConstraint(): ProductCategoryConditionConstraint
    {
        return new ProductCategoryConditionConstraint();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): CmsSlotBlockProductCategoryGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockProductCategoryGuiDependencyProvider::FACADE_TRANSLATOR);
    }
}
