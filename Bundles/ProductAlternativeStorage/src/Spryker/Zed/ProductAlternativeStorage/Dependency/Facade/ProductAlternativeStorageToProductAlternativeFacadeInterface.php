<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeCriteriaTransfer;

interface ProductAlternativeStorageToProductAlternativeFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeCriteriaTransfer $productAlternativeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativeCollection(
        ProductAlternativeCriteriaTransfer $productAlternativeCriteriaTransfer
    ): ProductAlternativeCollectionTransfer;
}
