<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Category;

use Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence\CmsSlotBlockProductCategoryGuiRepositoryInterface;

class CmsSlotBlockProductCategoryGuiCategoryReader implements CmsSlotBlockProductCategoryGuiCategoryReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence\CmsSlotBlockProductCategoryGuiRepositoryInterface
     */
    protected $cmsSlotBlockProductCategoryGuiRepository;

    /**
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Persistence\CmsSlotBlockProductCategoryGuiRepositoryInterface $cmsSlotBlockProductCategoryGuiRepository
     */
    public function __construct(CmsSlotBlockProductCategoryGuiRepositoryInterface $cmsSlotBlockProductCategoryGuiRepository)
    {
        $this->cmsSlotBlockProductCategoryGuiRepository = $cmsSlotBlockProductCategoryGuiRepository;
    }

    /**
     * @return int[]
     */
    public function getCategories(): array
    {
        $categoryCollectionTransfer = $this->cmsSlotBlockProductCategoryGuiRepository
            ->getCategories();

        $categoryIds = [];

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $categoryIds[$categoryTransfer->getName()] = $categoryTransfer->getIdCategory();
        }

        return $categoryIds;
    }
}
