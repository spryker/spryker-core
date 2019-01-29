<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Dependency\Facade;

use Generated\Shared\Transfer\ProductListMapTransfer;

class ProductListToProductListSearchFacadeBridge implements ProductListToProductListSearchFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface
     */
    protected $productListSearchFacade;

    /**
     * @param \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface $productListSearchFacade
     */
    public function __construct($productListSearchFacade)
    {
        $this->productListSearchFacade = $productListSearchFacade;
    }

    /**
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductListMapTransfer $productListMapTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListMapTransfer
     */
    public function mapProductDataToProductListMapTransfer(array $productData, ProductListMapTransfer $productListMapTransfer): ProductListMapTransfer
    {
        return $this->productListSearchFacade
            ->mapProductDataToProductListMapTransfer($productData, $productListMapTransfer);
    }
}
