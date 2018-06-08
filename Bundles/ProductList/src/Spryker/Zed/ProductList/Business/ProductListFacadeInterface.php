<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business;

use Generated\Shared\Transfer\ProductListTransfer;

interface ProductListFacadeInterface
{
    /**
     * Specification:
     * - Creates a Product List entity.
     * - Creates relations to categories.
     * - Creates relations to concrete products.
     * - Finds a Product List by ProductListTransfer::idProductList in the transfer.
     * - Updates fields in a Product List entity.
     * - Updates relations to categories.
     * - Updates relations to concrete products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function saveProductList(ProductListTransfer $productListTransfer): ProductListTransfer;

    /**
     * Specification:
     * - Finds a Product List by ProductListTransfer::idProductList in the transfer.
     * - Deletes Product List.
     * - Deletes relations to categories.
     * - Deletes relations to concrete products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function deleteProductList(ProductListTransfer $productListTransfer): void;
}
