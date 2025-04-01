<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business\Saver;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementEntityManagerInterface;

class ProductAbstractTypeSaver implements ProductAbstractTypeSaverInterface
{
    /**
     * @param \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementEntityManagerInterface $entityManager
     */
    public function __construct(
        protected SspServiceManagementEntityManagerInterface $entityManager
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

        $this->entityManager->saveProductAbstractTypesForProductAbstract(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
            $productAbstractTypeIds,
        );

        return $productAbstractTransfer;
    }
}
