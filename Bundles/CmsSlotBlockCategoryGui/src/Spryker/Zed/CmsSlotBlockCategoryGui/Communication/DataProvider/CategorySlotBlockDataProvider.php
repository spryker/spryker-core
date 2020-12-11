<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCategoryGui\Communication\DataProvider;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Spryker\Zed\CmsSlotBlockCategoryGui\Communication\Form\CategorySlotBlockConditionForm;
use Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToStoreFacadeInterface;
use Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToTranslatorFacadeInterface;

class CategorySlotBlockDataProvider implements CategorySlotBlockDataProviderInterface
{
    protected const KEY_OPTION_ALL_CATEGORIES = 'All Category Pages';
    protected const KEY_OPTION_SPECIFIC_CATEGORY = 'Specific Category Pages';

    /**
     * @var \Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var \Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        CmsSlotBlockCategoryGuiToCategoryFacadeInterface $categoryFacade,
        CmsSlotBlockCategoryGuiToLocaleFacadeInterface $localeFacade,
        CmsSlotBlockCategoryGuiToTranslatorFacadeInterface $translatorFacade,
        CmsSlotBlockCategoryGuiToStoreFacadeInterface $storeFacade
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
        $this->translatorFacade = $translatorFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            CategorySlotBlockConditionForm::OPTION_ALL_ARRAY => $this->getAllOptions(),
            CategorySlotBlockConditionForm::OPTION_CATEGORY_ARRAY => $this->getCategories(),
        ];
    }

    /**
     * @return array
     */
    protected function getAllOptions(): array
    {
        return [
            $this->translatorFacade->trans(static::KEY_OPTION_ALL_CATEGORIES) => true,
            $this->translatorFacade->trans(static::KEY_OPTION_SPECIFIC_CATEGORY) => false,
        ];
    }

    /**
     * @return int[]
     */
    protected function getCategories(): array
    {
        $categoryCollectionTransfer = $this->categoryFacade
            ->getAllCategoryCollection(
                $this->localeFacade->getCurrentLocale(),
                $this->storeFacade->getCurrentStore()->getName()
            );

        return $this->getCategoryIdsFromCollection($categoryCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return int[]
     */
    protected function getCategoryIdsFromCollection(CategoryCollectionTransfer $categoryCollectionTransfer): array
    {
        $categoryIds = [];

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $categoryIds[$categoryTransfer->getName()] = $categoryTransfer->getIdCategory();
        }

        return $categoryIds;
    }
}
