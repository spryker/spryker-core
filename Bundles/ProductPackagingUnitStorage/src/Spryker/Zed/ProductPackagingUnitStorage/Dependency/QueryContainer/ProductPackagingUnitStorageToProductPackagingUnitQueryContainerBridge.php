<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Dependency\QueryContainer;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;

class ProductPackagingUnitStorageToProductPackagingUnitQueryContainerBridge implements ProductPackagingUnitStorageToProductQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\Propel\AbstractSpyProductPackagingLeadProductQuery
     */
    protected $productPackagingUnitQueryContainer;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\Propel\AbstractSpyProductPackagingLeadProductQuery $productPackagingUnitQueryContainer
     */
    public function __construct($productPackagingUnitQueryContainer)
    {
        $this->productPackagingUnitQueryContainer = $productPackagingUnitQueryContainer;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract(): SpyProductAbstractQuery
    {
        return $this->productPackagingUnitQueryContainer->query();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProduct(): SpyProductQuery
    {
        return $this->productPackagingUnitQueryContainer->queryProduct();
    }
}
