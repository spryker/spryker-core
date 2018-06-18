<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Dependency\Facade;


interface ProductAlternativeStorageToProductAlternativeFacadeInterface
{
    /**
     * @param int[] $productAlternativeIds
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer[]
     */
    public function findProductAlternativeTransfers(array $productAlternativeIds): array;
}
