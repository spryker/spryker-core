<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Dependency\QueryContainer;

class ProductCategoryStorageToProductQueryContainerBridge implements ProductCategoryStorageToProductQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct($productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryAllProductAbstractLocalizedAttributes()
    {
        return $this->productQueryContainer->queryAllProductAbstractLocalizedAttributes();
    }
}
