<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Persistence;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;

interface ProductDiscontinuedRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer|null
     */
    public function findProductDiscontinuedByProductId(ProductDiscontinuedTransfer $productDiscontinuedTransfer): ?ProductDiscontinuedTransfer;

    /**
     * @param int[] $productIds
     *
     * @return bool
     */
    public function areAllConcreteProductsDiscontinued(array $productIds): bool;

    /**
     * @param int[] $productConcreteIds
     *
     * @return bool
     */
    public function isAnyProductConcreteDiscontinued(array $productConcreteIds): bool;

    /**
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductsToDeactivate(): ProductDiscontinuedCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
    ): ProductDiscontinuedCollectionTransfer;

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function checkIfProductDiscontinuedBySku(string $sku): bool;

    /**
     * @return int[]
     */
    public function findProductAbstractIdsWithDiscontinuedConcrete(): array;
}
