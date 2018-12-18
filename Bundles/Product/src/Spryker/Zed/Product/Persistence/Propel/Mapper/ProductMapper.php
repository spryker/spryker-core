<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyProduct;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Propel\Runtime\Collection\ObjectCollection;

class ProductMapper implements ProductMapperInterface
{
    /**
     * @param \Orm\Zed\Category\Persistence\SpyProduct $spyCategory
     * @param \Generated\Shared\Transfer\ProductTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTransfer
     */
    public function mapProduct(SpyProduct $spyCategory, ProductTransfer $categoryTransfer): ProductTransfer
    {
        return $categoryTransfer->fromArray($spyCategory->toArray(), true);
    }
}
