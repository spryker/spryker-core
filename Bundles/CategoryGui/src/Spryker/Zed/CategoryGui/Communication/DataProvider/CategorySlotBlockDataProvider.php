<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\DataProvider;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Spryker\Zed\CategoryGui\Communication\Form\CategorySlotBlockConditionForm;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface;

class CategorySlotBlockDataProvider
{
    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CategoryGuiToCategoryQueryContainerInterface $categoryQueryContainer,
        CategoryGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => CmsSlotBlockTransfer::class,
            CategorySlotBlockConditionForm::OPTION_CATEGORY_ARRAY => $this->getCategories(),
        ];
    }

    /**
     * @return array
     */
    protected function getCategories(): array
    {
        $idLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();
        /** @var \Orm\Zed\Category\Persistence\SpyCategory[] $categoryCollection */
        $categoryCollection = $this->categoryQueryContainer
            ->queryCategory($idLocale)
            ->find();

        $categories = [];

        foreach ($categoryCollection as $categoryEntity) {
            $categoryName = $categoryEntity->getLocalisedAttributes($idLocale)->getFirst()->getName();
            $categories[$categoryName] = $categoryEntity->getIdCategory();
        }

        return $categories;
    }
}
