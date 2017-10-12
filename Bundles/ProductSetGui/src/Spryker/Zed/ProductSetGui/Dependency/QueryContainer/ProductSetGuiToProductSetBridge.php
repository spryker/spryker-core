<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Dependency\QueryContainer;

class ProductSetGuiToProductSetBridge implements ProductSetGuiToProductSetInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface $productSetQueryContainer
     */
    public function __construct($productSetQueryContainer)
    {
        $this->productSetQueryContainer = $productSetQueryContainer;
    }

    /**
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryProductSet()
    {
        return $this->productSetQueryContainer->queryProductSet();
    }
}
