<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Reader\Category;

use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToCategoryFacadeInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToStoreFacadeInterface;

class CategoryReader implements CategoryReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\CmsSlotBlockProductCategoryGui\Dependency\Facade\CmsSlotBlockProductCategoryGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        CmsSlotBlockProductCategoryGuiToCategoryFacadeInterface $categoryFacade,
        CmsSlotBlockProductCategoryGuiToLocaleFacadeInterface $localeFacade,
        CmsSlotBlockProductCategoryGuiToStoreFacadeInterface $storeFacade
    ) {
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return int[]
     */
    public function getCategories(): array
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $categoryCollectionTransfer = $this->categoryFacade->getAllCategoryCollection(
            $localeTransfer,
            $this->storeFacade->getCurrentStore()->getName()
        );

        $categoryIds = [];

        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $categoryIds[$categoryTransfer->getName()] = $categoryTransfer->getIdCategory();
        }

        return $categoryIds;
    }
}
