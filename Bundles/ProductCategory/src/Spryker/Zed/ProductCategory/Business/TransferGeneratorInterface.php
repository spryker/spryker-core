<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\ProductCategoryTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;

interface TransferGeneratorInterface
{

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory $productCategoryEntity
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer
     */
    public function convertProductCategory(SpyProductCategory $productCategoryEntity);

    /**
     * @param SpyProductCategory[]|ObjectCollection $productCategoryEntityList
     *
     * @return ProductCategoryTransfer[]
     */
    public function convertProductCategoryCollection(ObjectCollection $productCategoryEntityList);

}
