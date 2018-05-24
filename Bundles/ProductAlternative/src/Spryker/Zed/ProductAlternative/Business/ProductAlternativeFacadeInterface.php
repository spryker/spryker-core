<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductAlternativeFacadeInterface
{
    /**
     * Specification:
     * - Creates alternative abstract product for concrete one
     * - Uses $idProduct to find for which concrete product alternative one will be created
     * - Uses $idProductAbstractAlternative as a reference to abstract product that will be an alternative one
     * - Returns transfer object of created alternative product
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): ProductAlternativeResponseTransfer;

    /**
     * Specification:
     * - Creates alternative concrete product for concrete one
     * - Uses $idProduct to find for which concrete product alternative one will be created
     * - Uses $idProductAbstractAlternative as a reference to abstract product that will be an alternative one
     * - Returns transfer object of created alternative product
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): ProductAlternativeResponseTransfer;

    /**
     * Specification:
     * - Retrieve all alternative concrete products for concrete product with id = $idProduct
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): ProductAlternativeCollectionTransfer;

    /**
     * Specification:
     * - Retrieve alternative product object by concrete product id
     * - Uses $productAlternativeTransfer to extract concrete product id
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductAlternativeByProductAlternativeId(ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeTransfer;
}
