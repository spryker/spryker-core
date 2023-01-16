<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeCriteriaTransfer;
use Generated\Shared\Transfer\ProductAlternativeListTransfer;
use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductAlternativeFacadeInterface
{
    /**
     * Specification:
     * - Searches for all alternatives for concrete product and returns an array, hydrated with data.
     * - Uses ProductAlternativeReader to find all product alternatives.
     * - Uses ProductAlternativeListHydrator to create ProductAlternativeList with data.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    public function getProductAlternativeListByIdProductConcrete(int $idProductConcrete): ProductAlternativeListTransfer;

    /**
     * Specification:
     * - Deletes product alternative by id.
     *
     * @api
     *
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function deleteProductAlternativeByIdProductAlternative(int $idProductAlternative): ProductAlternativeResponseTransfer;

    /**
     * Specification:
     * - Persists product alternative stored in product concrete transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductAlternative(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;

    /**
     * Specification:
     * - Checks if all given products have alternatives.
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return bool
     */
    public function doAllConcreteProductsHaveAlternatives(array $productIds): bool;

    /**
     * Specification:
     * - Checks if alternative products should be shown for product concrete.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isAlternativeProductApplicable(int $idProductConcrete): bool;

    /**
     * Specification:
     * - Finds product abstract ids which concrete products has alternatives.
     *
     * @api
     *
     * @return array<int>
     */
    public function findProductAbstractIdsWhichConcreteHasAlternative(): array;

    /**
     * Specification:
     * - Fetches a collection of product alternatives from the Persistence.
     * - Uses `ProductAlternativeCriteriaTransfer.pagination.limit` and `ProductAlternativeCriteriaTransfer.pagination.offset` to paginate results with limit and offset.
     * - Returns `ProductAlternativeCollectionTransfer` filled with found product alternatives.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAlternativeCriteriaTransfer $productAlternativeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativeCollection(
        ProductAlternativeCriteriaTransfer $productAlternativeCriteriaTransfer
    ): ProductAlternativeCollectionTransfer;
}
