<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductList\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductListBuilder;
use Generated\Shared\Transfer\ProductListTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductListHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function haveProductList(array $seed = []): ProductListTransfer
    {
        $productListTransfer = (new ProductListBuilder($seed))->build();

        $productListResponseTransfer = $this->getLocator()
            ->productList()
            ->facade()
            ->createProductList($productListTransfer);

        return $productListResponseTransfer->getProductList();
    }
}
