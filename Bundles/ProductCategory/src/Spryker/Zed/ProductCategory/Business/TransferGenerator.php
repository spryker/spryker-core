<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\ProductCategoryTransfer;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Propel\Runtime\Collection\ObjectCollection;

/**
 * @deprecated Class will be removed with next major release
 */
class TransferGenerator implements TransferGeneratorInterface
{

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory $productCategoryEntity
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer
     */
    public function convertProductCategory(SpyProductCategory $productCategoryEntity)
    {
        return (new ProductCategoryTransfer())
            ->fromArray($productCategoryEntity->toArray());
    }

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[]|\Propel\Runtime\Collection\ObjectCollection $productCategoryEntityList
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer[]
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
