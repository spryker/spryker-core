<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductRelation\Sorting;

use Generated\Shared\Transfer\StorageProductAbstractRelationTransfer;

class RelationSorter implements RelationSorterInterface
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer[] $relationProducts
     *
     * @return \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer[]
     */
    public function sort(array $relationProducts)
    {
        usort($relationProducts, function (StorageProductAbstractRelationTransfer $leftProduct, StorageProductAbstractRelationTransfer $rightProduct) {
            return strnatcmp($leftProduct->getOrder(), $rightProduct->getOrder());
        });

        return $relationProducts;
    }

}
