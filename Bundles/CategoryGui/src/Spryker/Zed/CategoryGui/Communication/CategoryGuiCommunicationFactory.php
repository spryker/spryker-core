<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication;

use Spryker\Zed\CategoryGui\CategoryGuiDependencyProvider;
use Spryker\Zed\CategoryGui\Communication\DataProvider\CategorySlotBlockDataProvider;
use Spryker\Zed\CategoryGui\Communication\Form\CategorySlotBlockConditionForm;
use Spryker\Zed\CategoryGui\Communication\Table\CategoryTable;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CategoryGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Table\CategoryTable
     */
    public function createCategoryTable(): CategoryTable
    {
        return new CategoryTable($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Form\CategorySlotBlockConditionForm
     */
    public function createCategorySlotBlockConditionForm(): CategorySlotBlockConditionForm
    {
        return new CategorySlotBlockConditionForm();
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\DataProvider\CategorySlotBlockDataProvider
     */
    public function createCategorySlotBlockDataProvider(): CategorySlotBlockDataProvider
    {
        return new CategorySlotBlockDataProvider($this->getCategoryQueryContainer(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): CategoryGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface
     */
    public function getCategoryQueryContainer(): CategoryGuiToCategoryQueryContainerInterface
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }
}
