<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Generated\Shared\Transfer\ProductAlternativeTransfer;

interface ProductAlternativeEntityManagerInterface
{
    /**
     * Specification:
     * - Creates new abstract product alternative for existing product concrete.
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function createProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): ProductAlternativeTransfer;

    /**
     * Specification:
     * - Creates new concrete product alternative for existing product concrete.
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function createProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): ProductAlternativeTransfer;

    /**
     * Specification:
     * - Updates existing product alternative.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function updateProductAlternative(ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeTransfer;

    /**
     * Specification:
     * - Deletes existing product alternative.
     * - Deletes only a link between products, thus products themselves are untouched.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return void
     */
    public function deleteProductAlternative(ProductAlternativeTransfer $productAlternativeTransfer): void;
}
