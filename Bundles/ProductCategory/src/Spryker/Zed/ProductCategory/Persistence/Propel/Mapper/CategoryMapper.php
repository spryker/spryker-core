<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence\Propel\Mapper;

use Propel\Runtime\Collection\ObjectCollection;

class CategoryMapper implements CategoryMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $spyProductCategoryCollection
     *
     * @return int[]
     */
    public function getIdsCategoryList(ObjectCollection $spyProductCategoryCollection): array
    {
        $idsCategory = [];
        /** @var \Orm\Zed\ProductCategory\Persistence\SpyProductCategory $spyProductCategory */
        foreach ($spyProductCategoryCollection as $spyProductCategory) {
            $idsCategory[] = $spyProductCategory->getFkCategory();
        }
        return $idsCategory;
    }
}
