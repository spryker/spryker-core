<?php

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilter\Business\Exception\ProductCategoryFilterNotFoundException;

class ProductCategoryFilterReader implements ProductCategoryFilterReaderInterface
{
    use RetrievesProductCategoryFilterEntity;

    /**
     * @param int $categoryId
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function findProductCategoryFilterByCategoryId($categoryId)
    {
        $productCategoryFilterEntity = $this->getProductCategoryFilterEntityByCategoryId($categoryId);

        if (!$productCategoryFilterEntity) {
            return null;
        }

        $productCategoryFilterTransfer = new ProductCategoryFilterTransfer();

        return $productCategoryFilterTransfer->fromArray($productCategoryFilterEntity->toArray(), true);
    }
}
