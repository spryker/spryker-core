<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductList;

use Generated\Shared\Transfer\ProductListTransfer;

interface ProductListReaderInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractBlacklistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function findProductList(ProductListTransfer $productListTransfer): ProductListTransfer;
}
