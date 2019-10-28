<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication;

use Spryker\Zed\CategoryGui\CategoryGuiDependencyProvider;
use Spryker\Zed\CategoryGui\Communication\DataProvider\CategorySlotBlockDataProvider;
use Spryker\Zed\CategoryGui\Communication\DataProvider\CategorySlotBlockDataProviderInterface;
use Spryker\Zed\CategoryGui\Communication\Form\CategorySlotBlockConditionForm;
use Spryker\Zed\CategoryGui\Communication\Form\Validator\Constraints\CategoryConditionsConstraint;
use Spryker\Zed\CategoryGui\Communication\Table\CategoryTable;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormBuilderInterface;

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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function createCategorySlotBlockConditionForm(FormBuilderInterface $builder): void
    {
        $categorySlotBlockDataProvider = $this->createCategorySlotBlockDataProvider();
        $categorySlotBlockConditionForm = new CategorySlotBlockConditionForm();
        $categorySlotBlockConditionForm->buildForm($builder, $categorySlotBlockDataProvider->getOptions());
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\DataProvider\CategorySlotBlockDataProviderInterface
     */
    public function createCategorySlotBlockDataProvider(): CategorySlotBlockDataProviderInterface
    {
        return new CategorySlotBlockDataProvider($this->getCategoryQueryContainer(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\CategoryGui\Communication\Form\Validator\Constraints\CategoryConditionsConstraint
     */
    public function createCategoryConditionsConstraint(): CategoryConditionsConstraint
    {
        return new CategoryConditionsConstraint();
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
