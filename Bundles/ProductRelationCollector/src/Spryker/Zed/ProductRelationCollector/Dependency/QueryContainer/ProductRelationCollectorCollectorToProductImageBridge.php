<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer;

class ProductRelationCollectorCollectorToProductImageBridge implements ProductRelationCollectorToProductImageInterface
{
    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $productImageQueryContainer;

    /**
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface $productImageQueryContainer
     */
    public function __construct($productImageQueryContainer)
    {
        $this->productImageQueryContainer = $productImageQueryContainer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryImageSetByProductAbstractId($idProductAbstract)
    {
         return $this->productImageQueryContainer->queryImageSetByProductAbstractId($idProductAbstract);
    }
}
