<?php

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;

class ProductCategoryFilterUpdater implements ProductCategoryFilterUpdaterInterface
{
    use RetrievesProductCategoryFilterEntity;

    /**
     * @param ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return ProductCategoryFilterTransfer
     */
    public function updateProductCategoryFilter(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        $productCategoryFilterEntity = $this->getProductCategoryFilterEntityByCategoryId($productCategoryFilterTransfer->getFkCategory());

        $productCategoryFilterEntity->fromArray($productCategoryFilterTransfer->modifiedToArray());
        $productCategoryFilterEntity->save();

        return $productCategoryFilterTransfer->fromArray($productCategoryFilterEntity->toArray(), true);
    }
}
