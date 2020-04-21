<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business\Grouper;

class ProductRelationStorageGrouper implements ProductRelationStorageGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer[] $productRelationTransfers
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[][][]
     */
    public function groupProductRelationsByProductAbstractAndStore(
        array $productRelationTransfers
    ): array {
        $productRelations = [];

        foreach ($productRelationTransfers as $productRelationTransfer) {
            foreach ($productRelationTransfer->getStoreRelation()->getStores() as $storeTransfer) {
                $productRelations[$productRelationTransfer->getFkProductAbstract()][$storeTransfer->getName()][] = $productRelationTransfer;
            }
        }

        return $productRelations;
    }
}
