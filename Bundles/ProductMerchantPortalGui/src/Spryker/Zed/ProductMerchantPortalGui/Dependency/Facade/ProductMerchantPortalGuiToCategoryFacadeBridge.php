<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

class ProductMerchantPortalGuiToCategoryFacadeBridge implements ProductMerchantPortalGuiToCategoryFacadeInterface
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
     * {@inheritDoc}
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return mixed[]
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this->categoryFacade->getTreeNodeChildrenByIdCategoryAndLocale($idCategory, $localeTransfer);
    }
}
