<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Dependency\QueryContainer;

class ProductAlternativeToProductCategoryQueryContainerBridge implements ProductAlternativeToProductCategoryQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface
     */
    protected $productCategoryQueryContainer;

    /**
     * @param \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface $productCategoryQueryContainer
     */
    public function __construct($productCategoryQueryContainer)
    {
        $this->productCategoryQueryContainer = $productCategoryQueryContainer;
    }

    /**
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryMappings()
    {
        return $this->productCategoryQueryContainer->queryProductCategoryMappings();
    }
}
