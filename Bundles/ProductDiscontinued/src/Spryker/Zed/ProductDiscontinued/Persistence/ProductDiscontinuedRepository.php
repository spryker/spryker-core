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
        $productDiscontinuedEntity = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->leftJoinWithSpyProductDiscontinuedNote()
            ->leftJoinWithProduct()
            ->filterByFkProduct($productDiscontinuedTransfer->getFkProduct())
            ->find();

        if ($productDiscontinuedEntity->count()) {
            return $this->getFactory()
                ->createProductDiscontinuedMapper()
                ->mapProductDiscontinuedTransfer($productDiscontinuedEntity->getFirst());
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductsToDeactivate(): ProductDiscontinuedCollectionTransfer
    {
        $productDiscontinuedEntityCollection = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->filterByActiveUntil(['max' => time()], Criteria::LESS_THAN)
            ->find();

        if ($productDiscontinuedEntityCollection->count()) {
            return $this->getFactory()
                ->createProductDiscontinuedMapper()
                ->mapTransferCollection($productDiscontinuedEntityCollection);
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

        $productDiscontinuedEntityCollection = $productDiscontinuedQuery->find();

        if ($productDiscontinuedEntityCollection->count()) {
            return $this->getFactory()
                ->createProductDiscontinuedMapper()
                ->mapTransferCollection($productDiscontinuedEntityCollection);
        }

        return new ProductDiscontinuedCollectionTransfer();
    }
}
