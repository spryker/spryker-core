<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Dependency\QueryContainer;

class ProductCategoryFilterStorageToProductCategoryFilterQueryContainerBridge implements ProductCategoryFilterStorageToProductCategoryFilterQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface
     */
    protected $productCategoryFilter;

    /**
     * @param \Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface $productCategoryFilter
     */
    public function __construct($productCategoryFilter)
    {
        $this->productCategoryFilter = $productCategoryFilter;
    }

    /**
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryFilter()
    {
        return $this->productCategoryFilter->queryProductCategoryFilter();
    }
}
