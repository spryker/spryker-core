<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAbstractDataFeed\Dependency\QueryContainer;

use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAbstractDataFeedToProductBridge implements ProductAbstractDataFeedToProductInterface
{

    /**
     * @var ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * ProductAbstractDataFeedToProductBridge constructor.
     *
     * @param ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct($productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract()
    {
        return $this->productQueryContainer
            ->queryProductAbstract();
    }

}