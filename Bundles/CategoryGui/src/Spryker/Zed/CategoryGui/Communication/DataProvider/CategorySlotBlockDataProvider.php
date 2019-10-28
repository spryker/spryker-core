<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\DataProvider;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CategoryGui\Communication\Form\CategorySlotBlockConditionForm;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface;

class CategorySlotBlockDataProvider implements CategorySlotBlockDataProviderInterface
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
            CategorySlotBlockConditionForm::OPTION_CATEGORY_IDS => $this->getCategoryIds(),
        ];
    }

    /**
     * @return int[]
     */
    protected function getCategoryIds(): array
    {
        $idLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();

        $categoryEntityCollection = $this->categoryQueryContainer
            ->queryCategory($idLocale)
            ->find();

        return $this->getCategoryIdsFromCollection($categoryEntityCollection, $idLocale);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Category\Persistence\SpyCategory[] $categoryEntityCollection
     * @param int $idLocale
     *
     * @return int[]
     */
    protected function getCategoryIdsFromCollection(ObjectCollection $categoryEntityCollection, int $idLocale): array
    {
        $categoryIds = [];

        foreach ($categoryEntityCollection as $categoryEntity) {
            $categoryName = $categoryEntity->getLocalisedAttributes($idLocale)->getFirst()->getName();
            $categoryIds[$categoryName] = $categoryEntity->getIdCategory();
        }

        return $categoryIds;
    }
}
