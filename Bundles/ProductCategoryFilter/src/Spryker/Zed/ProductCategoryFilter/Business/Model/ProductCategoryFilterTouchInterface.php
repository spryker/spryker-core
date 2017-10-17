<?php


namespace Spryker\Zed\ProductCategoryFilter\Business\Model;


use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

interface ProductCategoryFilterTouchInterface
{

    /**
     * @param ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return mixed
     */
    public function touchProductCategoryFilterActive(ProductCategoryFilterTransfer $productCategoryFilterTransfer);

    /**
     * @param ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return mixed
     */
    public function touchProductCategoryFilterDeleted(ProductCategoryFilterTransfer $productCategoryFilterTransfer);
}