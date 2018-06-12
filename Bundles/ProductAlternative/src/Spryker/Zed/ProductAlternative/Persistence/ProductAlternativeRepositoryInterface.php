<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;

interface ProductAlternativeRepositoryInterface
{
    /**
     * Specification:
     * - Retrieves all alternative concrete products for concrete product with id = $idProduct
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesForProductConcrete(int $idProductConcrete): ProductAlternativeCollectionTransfer;

    /**
     * Specification:
     * - Retrieves product alternative object by concrete product id
     * - Uses $productAlternativeTransfer to extract concrete product id
     *
     * @api
     *
     * @param int $idProductAlternative
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductAlternativeByIdProductAlternative(int $idProductAlternative): ?ProductAlternativeTransfer;

    /**
     * Specification:
     * - Retrieves product abstract alternative for concrete base product.
     * - $idBaseProduct is the id of product concrete which has an alternative.
     * - $idProductAbstract is the product abstract id which is an alternative.
     *
     * @api
     *
     * @param int $idBaseProduct
     * @param int $idProductAbstract
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductAbstractAlternative(int $idBaseProduct, int $idProductAbstract): ?ProductAlternativeTransfer;

    /**
     * Specification:
     * - Retrieves product concrete alternative for concrete base product.
     * - $idBaseProduct is the id of product concrete which has an alternative.
     * - $idProductConcrete is the product id which is an alternative.
     *
     * @api
     *
     * @param int $idBaseProduct
     * @param int $idProductConcrete
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductConcreteAlternative(int $idBaseProduct, int $idProductConcrete): ?ProductAlternativeTransfer;
}
