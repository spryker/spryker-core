<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Dependency\QueryContainer;

class ProductToSalesProductConnectorQueryContainerBridge implements ProductToSalesProductConnectorQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $ProductQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $ProductQueryContainer
     */
    public function __construct($ProductQueryContainer)
    {
        $this->ProductQueryContainer = $ProductQueryContainer;
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductConcreteBySku($sku)
    {
        return $this->ProductQueryContainer->queryProductConcreteBySku($sku);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey()
    {
        return $this->ProductQueryContainer->queryProductAttributeKey();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProduct()
    {
        return $this->ProductQueryContainer->queryProduct();
    }
}
