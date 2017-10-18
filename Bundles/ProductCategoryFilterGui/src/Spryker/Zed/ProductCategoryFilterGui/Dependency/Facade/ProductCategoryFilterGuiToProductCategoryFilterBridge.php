<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

class ProductCategoryFilterGuiToProductCategoryFilterBridge implements ProductCategoryFilterGuiToProductCategoryFilterInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Business\ProductCategoryFilterFacadeInterface
     */
    protected $productCategoryFilterFacade;

    /**
     * @param \Spryker\Zed\ProductCategoryFilter\Business\ProductCategoryFilterFacadeInterface $productCategoryFilterFacade
     */
    public function __construct($productCategoryFilterFacade)
    {
        $this->productCategoryFilterFacade = $productCategoryFilterFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function createProductCategoryFilter(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        return $this->productCategoryFilterFacade->createProductCategoryFilter($productCategoryFilterTransfer);
    }

    /**
     * @param int $categoryId
     *
     * @return void
     */
    public function deleteProductCategoryFilterByCategoryId($categoryId)
    {
        $this->productCategoryFilterFacade->deleteProductCategoryFilterByCategoryId($categoryId);
    }
}
