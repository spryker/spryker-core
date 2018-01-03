<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\QueryContainer;

class ProductPageToPriceProductQueryContainerBridge implements ProductPageToPriceProductQueryContainerInterface
{

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $priceProductQueryContainer;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     */
    public function __construct($priceProductQueryContainer)
    {
        $this->priceProductQueryContainer = $priceProductQueryContainer;
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProduct()
    {
        return $this->priceProductQueryContainer->queryPriceProduct();
    }

}
