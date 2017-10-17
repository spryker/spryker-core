<?php


namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

interface ProductCategoryFilterReaderInterface
{
    /**
     * @param int $categoryId
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function findProductCategoryFilterByCategoryId($categoryId);
}
