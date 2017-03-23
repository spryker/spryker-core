<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductRelation\Sorting;

interface RelationSorterInterface
{

    /**
     * @param array|\Generated\Shared\Transfer\StorageProductAbstractRelationTransfer[] $relationProducts
     *
     * @return array|\Generated\Shared\Transfer\StorageProductAbstractRelationTransfer[]
     */
    public function sort(array $relationProducts);

}
