<?php

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilter;

class ProductCategoryFilterCreator implements ProductCategoryFilterCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryFilterTransfer
     */
    public function createProductCategoryFilter(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        $productCategoryFilterEntity = new SpyProductCategoryFilter();
        $productCategoryFilterEntity->fromArray($productCategoryFilterTransfer->toArray());

        $productCategoryFilterEntity->save();

        $productCategoryFilterTransfer->setIdProductCategoryFilter($productCategoryFilterEntity->getIdProductCategoryFilter());

        return $productCategoryFilterTransfer;
    }
}
