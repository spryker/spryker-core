<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCategoryGui\Communication;

use Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockCategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CmsSlotBlockCategoryGui\CmsSlotBlockCategoryGuiDependencyProvider;
use Spryker\Zed\CmsSlotBlockCategoryGui\Communication\DataProvider\CategorySlotBlockDataProvider;
use Spryker\Zed\CmsSlotBlockCategoryGui\Communication\DataProvider\CategorySlotBlockDataProviderInterface;
use Spryker\Zed\CmsSlotBlockCategoryGui\Communication\Form\CategorySlotBlockConditionForm;
use Spryker\Zed\CmsSlotBlockCategoryGui\Communication\Form\Validator\Constraints\CategoryConditionsConstraint;
use Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CategoryGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsSlotBlockCategoryGui\Communication\Form\CategorySlotBlockConditionForm
     */
    public function createCategorySlotBlockConditionForm(): CategorySlotBlockConditionForm
    {
        return new CategorySlotBlockConditionForm();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockCategoryGui\Communication\DataProvider\CategorySlotBlockDataProviderInterface
     */
    public function createCategorySlotBlockDataProvider(): CategorySlotBlockDataProviderInterface
    {
        return new CategorySlotBlockDataProvider(
            $this->getCategoryFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockCategoryGui\Communication\Form\Validator\Constraints\CategoryConditionsConstraint
     */
    public function createCategoryConditionsConstraint(): CategoryConditionsConstraint
    {
        return new CategoryConditionsConstraint();
    }

    public function getCategoryFacade(): CmsSlotBlockCategoryGuiToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockCategoryGuiDependencyProvider::FACADE_CATEGORY);
    }

    public function getLocaleFacade(): CmsSlotBlockCategoryGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockCategoryGuiDependencyProvider::FACADE_LOCALE);
    }
}
