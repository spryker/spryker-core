<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Dependency\QueryContainer;

class ProductGroupStorageToProductGroupQueryContainerBridge implements ProductGroupStorageToProductGroupQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface
     */
    protected $productGroupQueryContainer;

    /**
     * @param \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface $productGroupQueryContainer
     */
    public function __construct($productGroupQueryContainer)
    {
        $this->productGroupQueryContainer = $productGroupQueryContainer;
    }

    /**
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function queryAllProductAbstractGroups()
    {
        return $this->productGroupQueryContainer->queryAllProductAbstractGroups();
    }
}
