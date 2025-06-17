<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;

class ProductAbstractTypeSaver implements ProductAbstractTypeSaverInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface $entityManager
     */
    public function __construct(
        protected SelfServicePortalEntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function saveProductAbstractTypesForProductAbstract(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        $productAbstractTransfer->requireIdProductAbstract();

        if ($productAbstractTransfer->getProductAbstractTypes()->count() === 0) {
            $this->entityManager->deleteProductAbstractTypesByProductAbstractId($productAbstractTransfer->getIdProductAbstractOrFail());

            return $productAbstractTransfer;
        }

        $productAbstractTypeIds = [];
        foreach ($productAbstractTransfer->getProductAbstractTypes() as $productAbstractType) {
            $productAbstractTypeIds[] = $productAbstractType->getIdProductAbstractTypeOrFail();
        }

        if ($productAbstractTypeIds === []) {
            return $productAbstractTransfer;
        }

        $this->entityManager->updateProductAbstractTypesForProductAbstract(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
            $productAbstractTypeIds,
        );

        return $productAbstractTransfer;
    }
}
