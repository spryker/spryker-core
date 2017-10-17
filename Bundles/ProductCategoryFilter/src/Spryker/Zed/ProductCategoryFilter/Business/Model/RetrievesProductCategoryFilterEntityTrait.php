<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

trait RetrievesProductCategoryFilterEntityTrait
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface
     */
    protected $productCategoryFilterQueryContainer;

    /**
     * @param int $categoryId
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilter
     */
    protected function getProductCategoryFilterEntityByCategoryId($categoryId)
    {
        return $this->productCategoryFilterQueryContainer
            ->queryProductCategoryFilterByCategoryId($categoryId)
            ->findOne();
    }
}
