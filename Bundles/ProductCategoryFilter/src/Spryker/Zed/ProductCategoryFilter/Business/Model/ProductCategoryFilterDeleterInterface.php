<?php


namespace Spryker\Zed\ProductCategoryFilter\Business\Model;


interface ProductCategoryFilterDeleterInterface
{
    /**
     * @param int $categoryId
     *
     * @return void
     */
    public function deleteProductCategoryFilterByCategoryId($categoryId);
}