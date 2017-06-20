<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Dependency\QueryContainer;

use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductLabelGuiToProductQueryContainerBridge implements ProductLabelGuiToProductQueryContainerInterface
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * ProductLabelGuiToProductQueryContainerBridge constructor.
     *
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract()
    {
        return $this
            ->productQueryContainer
            ->queryProductAbstract();
    }

}
