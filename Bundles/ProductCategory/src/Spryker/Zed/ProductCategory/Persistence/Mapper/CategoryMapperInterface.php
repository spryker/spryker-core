<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface CategoryMapperInterface
{
    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[]|\Propel\Runtime\Collection\ObjectCollection $categoryEntities
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function mapCategoryCollection(ObjectCollection $categoryEntities, CategoryCollectionTransfer $categoryCollectionTransfer): CategoryCollectionTransfer;
}
