<?php

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

class ProductCategoryFilterDeleter implements ProductCategoryFilterDeleterInterface
{
    use RetrievesProductCategoryFilterEntity;

    /**
     * @param int $categoryId
     *
     * @return void
     */
    public function deleteProductCategoryFilterByCategoryId($categoryId)
    {
        $productCategoryFilterEntity = $this->getProductCategoryFilterEntityByCategoryId($categoryId);
        $productCategoryFilterEntity->delete();
    }
}
