<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductClassConditionsTransfer;
use Generated\Shared\Transfer\ProductClassCriteriaTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ProductAbstractClassExpander implements ProductAbstractClassExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     */
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expandProductAbstractWithProductClasses(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        if ($productAbstractTransfer->getIdProductAbstract() === null) {
            return $productAbstractTransfer;
        }

        $productClassCriteriaTransfer = new ProductClassCriteriaTransfer();
        $productClassConditionsTransfer = new ProductClassConditionsTransfer();
        $productClassConditionsTransfer->setProductAbstractIds([$productAbstractTransfer->getIdProductAbstract()]);
        $productClassCriteriaTransfer->setProductClassConditions($productClassConditionsTransfer);

        $productClassCollectionTransfer = $this->selfServicePortalRepository->getProductClassCollection($productClassCriteriaTransfer);

        return $productAbstractTransfer->setProductClasses($productClassCollectionTransfer->getProductClasses());
    }
}
