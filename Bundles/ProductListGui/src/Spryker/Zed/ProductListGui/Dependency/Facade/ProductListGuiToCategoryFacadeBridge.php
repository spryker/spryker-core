<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Dependency\Facade;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

class ProductListGuiToCategoryFacadeBridge implements ProductListGuiToCategoryFacadeInterface
{
    /**
     * @var \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\Category\Business\CategoryFacadeInterface $categoryFacade
     */
    public function __construct($categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getAllCategoryCollection(LocaleTransfer $localeTransfer, ?string $storeName = null): CategoryCollectionTransfer
    {
        return $this->categoryFacade->getAllCategoryCollection($localeTransfer, $storeName);
    }
}
