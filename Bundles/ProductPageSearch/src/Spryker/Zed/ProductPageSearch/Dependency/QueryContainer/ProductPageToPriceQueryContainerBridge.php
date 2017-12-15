<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\QueryContainer;

class ProductPageToPriceQueryContainerBridge implements ProductPageToPriceQueryContainerInterface
{

    /**
     * @var \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface
     */
    protected $priceQueryContainer;

    /**
     * @param \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface $priceQueryContainer
     */
    public function __construct($priceQueryContainer)
    {
        $this->priceQueryContainer = $priceQueryContainer;
    }

    /**
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryAllPriceProducts()
    {
        return $this->priceQueryContainer->queryAllPriceProducts();
    }

}
