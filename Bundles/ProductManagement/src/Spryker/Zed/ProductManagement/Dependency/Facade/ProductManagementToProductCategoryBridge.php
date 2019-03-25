<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

class ProductManagementToProductCategoryBridge implements ProductManagementToProductCategoryInterface
{
    /**
     * @var \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface $categoryFacade
     */
    public function __construct($categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByIdProductAbstract(int $idProductAbstract, LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        return $this->categoryFacade->getCategoryTransferCollectionByIdProductAbstract($idProductAbstract, $localeTransfer);
    }
}
