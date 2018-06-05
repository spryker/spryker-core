<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternativeTable;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeTableTransfer;

interface ProductAlternativeTableHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer $productAlternativeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTableTransfer
     */
    public function hydrateProductAlternativeTable(ProductAlternativeCollectionTransfer $productAlternativeCollectionTransfer): ProductAlternativeTableTransfer;
}
