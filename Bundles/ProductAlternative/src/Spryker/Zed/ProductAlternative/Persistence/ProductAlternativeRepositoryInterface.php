<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;

interface ProductAlternativeRepositoryInterface
{
    /**
     * Specification:
     * - Get product alternative query.
     *
     * @api
     *
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    public function queryProductAlternative(): SpyProductAlternativeQuery;

    /**
     * Specification:
     * - Get product alternative query filtered by product concrete id (non-alternative).
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    public function queryProductAlternativeByIdProductConcrete(int $idProductConcrete): SpyProductAlternativeQuery;

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
    public function getProductAlternativeByIdProductAlternative(ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeTransfer;
}
