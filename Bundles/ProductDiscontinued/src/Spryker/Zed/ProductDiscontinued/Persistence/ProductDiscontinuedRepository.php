<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Persistence;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedPersistenceFactory getFactory()
 */
class ProductDiscontinuedRepository extends AbstractRepository implements ProductDiscontinuedRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer|null
     */
    public function findProductDiscontinuedByProductId(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer
    ): ?ProductDiscontinuedTransfer {
        $productDiscontinuedQuery = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->leftJoinWithSpyProductDiscontinuedNote()
            ->leftJoinWithProduct()
            ->filterByFkProduct($productDiscontinuedTransfer->getFkProduct());

        $productDiscontinuedEntityTransfers = $this->buildQueryFromCriteria($productDiscontinuedQuery)->find();
        if (count($productDiscontinuedEntityTransfers)) {
            return $this->getFactory()
                ->createProductDiscontinuedMapper()
                ->mapProductDiscontinuedTransfer($productDiscontinuedEntityTransfers[0]);
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductsToDeactivate(): ProductDiscontinuedCollectionTransfer
    {
        $productDiscontinuedQuery = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->filterByActiveUntil(['max' => time()], Criteria::LESS_THAN);
        $productDiscontinuedEntityTransfer = $this->buildQueryFromCriteria($productDiscontinuedQuery)->find();

        if ($productDiscontinuedEntityTransfer) {
            return $this->getFactory()
                ->createProductDiscontinuedMapper()
                ->mapTransferCollection($productDiscontinuedEntityTransfer);
        }

        return new ProductDiscontinuedCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
    ): ProductDiscontinuedCollectionTransfer {
        $productDiscontinuedQuery = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->leftJoinWithSpyProductDiscontinuedNote()
            ->leftJoinWithProduct();

        if ($criteriaFilterTransfer->getIds()) {
            $productDiscontinuedQuery
                ->filterByIdProductDiscontinued_In($criteriaFilterTransfer->getIds());
        }

        $productDiscontinuedEntityTransfers = $this->buildQueryFromCriteria($productDiscontinuedQuery)->find();

        if ($productDiscontinuedEntityTransfers) {
            return $this->getFactory()
                ->createProductDiscontinuedMapper()
                ->mapTransferCollection($productDiscontinuedEntityTransfers);
        }

        return new ProductDiscontinuedCollectionTransfer();
    }
}
