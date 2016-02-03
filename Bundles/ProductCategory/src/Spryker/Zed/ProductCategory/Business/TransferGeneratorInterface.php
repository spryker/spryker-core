<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Business;

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
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[]|\Propel\Runtime\Collection\ObjectCollection $productCategoryEntityList
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer[]
     */
    public function convertProductCategoryCollection(ObjectCollection $productCategoryEntityList);

}
