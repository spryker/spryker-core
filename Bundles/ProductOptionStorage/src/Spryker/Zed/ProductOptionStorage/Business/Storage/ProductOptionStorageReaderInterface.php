<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Business\Storage;

interface ProductOptionStorageReaderInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsWithDeactivatedGroups(array $productAbstractIds): array;
}
