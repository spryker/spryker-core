<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Dependency\QueryContainer;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;

class ProductStorageToProductQueryContainerBridge implements ProductStorageToProductQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct($productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryAllProductAbstractLocalizedAttributes()
    {
        return $this->productQueryContainer->queryAllProductAbstractLocalizedAttributes();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function queryAllProductLocalizedAttributes()
    {
        return $this->productQueryContainer->queryAllProductLocalizedAttributes();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    public function queryProductAttributeKey()
    {
        return $this->productQueryContainer->queryProductAttributeKey();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProduct()
    {
        return $this->productQueryContainer->queryProduct();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract(): SpyProductAbstractQuery
    {
        return $this->productQueryContainer->queryProductAbstract();
    }
}
