<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Dependency\QueryContainer;

class ProductPackagingUnitStorageToProductPackagingUnitQueryContainerBridge implements ProductPackagingUnitStorageToProductPackagingUnitQueryContainerInterface
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
}
