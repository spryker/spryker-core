<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Dependency\QueryContainer;

class ProductBundleToStockQueryContainerBridge implements ProductBundleToStockQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    protected $stockQueryContainer;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface $stockQueryContainer
     */
    public function __construct($stockQueryContainer)
    {
        $this->stockQueryContainer = $stockQueryContainer;
    }

    /**
     * @param int $idProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockByProducts($idProduct)
    {
         return $this->stockQueryContainer->queryStockByProducts($idProduct);
    }
}
