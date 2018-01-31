<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Dependency\QueryContainer;

class PriceProductStorageToPriceProductQueryContainerBridge implements PriceProductStorageToPriceProductQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $priceProductQueryContainer;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceQueryContainer
     */
    public function __construct($priceQueryContainer)
    {
        $this->priceProductQueryContainer = $priceQueryContainer;
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProduct()
    {
        return $this->priceProductQueryContainer->queryPriceProduct();
    }
}
