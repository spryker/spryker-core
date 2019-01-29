<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductList;

use Generated\Shared\Transfer\ProductListMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;

class ProductDataToProductListMapTransferMapper implements ProductDataToProductListMapTransferMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function mapProductDataProductList(array $productData, ProductListMapTransfer $productListMapTransfer): ProductListMapTransfer
    {
        return $productListMapTransfer->fromArray($productData[ProductPageSearchTransfer::PRODUCT_LIST_MAP]);
    }
}
