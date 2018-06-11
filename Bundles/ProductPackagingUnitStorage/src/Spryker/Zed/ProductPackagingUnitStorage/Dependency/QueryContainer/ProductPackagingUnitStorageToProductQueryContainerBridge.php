<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Dependency\QueryContainer;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;

class ProductPackagingUnitStorageToProductQueryContainerBridge implements ProductPackagingUnitStorageToProductQueryContainerInterface
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
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract(): SpyProductAbstractQuery
    {
        return $this->productQueryContainer->queryProductAbstract();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProduct(): SpyProductQuery
    {
        return $this->productQueryContainer->queryProduct();
    }
}
