<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business\Grouper;

interface ProductRelationStorageGrouperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductRelationTransfer> $productRelationTransfers
     *
     * @return array<array<array<\Generated\Shared\Transfer\ProductRelationTransfer>>>
     */
    public function groupProductRelationsByProductAbstractAndStore(
        array $productRelationTransfers
    ): array;
}
