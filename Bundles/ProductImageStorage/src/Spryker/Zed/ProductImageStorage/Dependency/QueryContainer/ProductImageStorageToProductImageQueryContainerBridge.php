<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Dependency\QueryContainer;

class ProductImageStorageToProductImageQueryContainerBridge implements ProductImageStorageToProductImageQueryContainerInterface
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
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductImageSetToProductImage()
    {
        return $this->productImageQueryContainer->queryProductImageSetToProductImage();
    }

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function queryProductImageSet()
    {
        return $this->productImageQueryContainer->queryProductImageSet();
    }
}
