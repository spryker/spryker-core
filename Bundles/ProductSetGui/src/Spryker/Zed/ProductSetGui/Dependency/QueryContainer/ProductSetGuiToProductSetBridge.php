<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Dependency\QueryContainer;

use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;

class ProductSetGuiToProductSetBridge implements ProductSetGuiToProductSetInterface
{

    /**
     * @var ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @param ProductSetQueryContainerInterface $productSetQueryContainer
     */
    public function __construct(ProductSetQueryContainerInterface $productSetQueryContainer)
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
