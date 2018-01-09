<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Dependency\QueryContainer;

class ProductOptionStorageToProductOptionQueryContainerBridge implements ProductOptionStorageToProductOptionQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     */
    public function __construct($productOptionQueryContainer)
    {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryAllProductOptionGroups()
    {
        return $this->productOptionQueryContainer->queryAllProductOptionGroups();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryAllProductAbstractProductOptionGroups()
    {
        return $this->productOptionQueryContainer->queryAllProductAbstractProductOptionGroups();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryAllProductOptionValues()
    {
        return $this->productOptionQueryContainer->queryAllProductOptionValues();
    }
}
