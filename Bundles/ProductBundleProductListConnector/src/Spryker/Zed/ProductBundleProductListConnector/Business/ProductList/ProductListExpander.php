<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList;

use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;

class ProductListExpander implements ProductListExpanderInterface
{
    /**
     * @var array|\Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface[]
     */
    protected $productListTypeExpanderList;

    /**
     * @param \Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type\ProductListTypeExpanderInterface[] $productListTypeExpanderList
     */
    public function __construct(array $productListTypeExpanderList)
    {
        $this->productListTypeExpanderList = $productListTypeExpanderList;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expandProductListWithProductBundle(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        $productListResponseTransfer = (new ProductListResponseTransfer())
            ->setProductList($productListTransfer);

        if (!$productListTransfer->getType()) {
            return $productListResponseTransfer;
        }

        foreach ($this->productListTypeExpanderList as $productListTypeExpander) {
            if (!$productListTypeExpander->isApplicable($productListTransfer)) {
                continue;
            }

            return $productListTypeExpander->expand($productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }
}
