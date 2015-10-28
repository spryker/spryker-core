<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\ProductCategoryTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;

class TransferGenerator implements TransferGeneratorInterface
{

    /**
     * @param SpyProductCategory $productCategoryEntity
     *
     * @return ProductCategoryTransfer
     */
    public function convertProductCategory(SpyProductCategory $productCategoryEntity)
    {
        return (new ProductCategoryTransfer())
            ->fromArray($productCategoryEntity->toArray());
    }

    /**
     * @param SpyProductCategory[]|ObjectCollection $productCategoryEntityList
     *
     * @return ProductCategoryTransfer[]
     */
    public function convertProductCategoryCollection(ObjectCollection $productCategoryEntityList)
    {
        $transferList = [];
        foreach ($productCategoryEntityList as $categoryEntity) {
            $transferList[] = $this->convertProductCategory($categoryEntity);
        }

        return $transferList;
    }

}
