<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\ProductCategoryTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategory;

interface TransferGeneratorInterface
{

    /**
     * @param SpyProductCategory $productCategoryEntity
     *
     * @return ProductCategoryTransfer
     */
    public function convertProductCategory(SpyProductCategory $productCategoryEntity);

    /**
     * @param SpyProductCategory[]|ObjectCollection $productCategoryEntityList
     *
     * @return ProductCategoryTransfer[]
     */
    public function convertProductCategoryCollection(ObjectCollection $productCategoryEntityList);

}
