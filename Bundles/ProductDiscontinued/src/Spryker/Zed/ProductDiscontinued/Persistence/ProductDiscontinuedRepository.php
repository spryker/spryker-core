<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Persistence;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
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
            ->filterByFkProduct($productDiscontinuedTransfer->getFkProduct());

        $productDiscontinuedEntityTransfer = $this->buildQueryFromCriteria($productDiscontinuedQuery)->findOne();
        if ($productDiscontinuedEntityTransfer) {
            return $this->getFactory()
                ->createProductDiscontinuedMapper()
                ->mapProductDiscontinuedTransfer($productDiscontinuedEntityTransfer);
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductsToDiactivate(): ProductDiscontinuedCollectionTransfer
    {
        $productDiscontinuedQuery = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->filterByActiveUntil(['max' => time()], Criteria::LESS_THAN)
            ->useProductQuery()
                ->filterByIsActive(true)
            ->endUse();
        $productDiscontinuedEntityTransfer = $this->buildQueryFromCriteria($productDiscontinuedQuery)->find();

        if ($productDiscontinuedEntityTransfer) {
            return $this->getFactory()
                ->createProductDiscontinuedMapper()
                ->mapTransferCollection($productDiscontinuedEntityTransfer);
        }

        return new ProductDiscontinuedCollectionTransfer();
    }
}
