<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\ItemTransferPackagingUnitExpander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;

interface ItemTransferPackagingUnitExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransferm
     * @param \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer|null $productAbstractPackagingStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expand(ItemTransfer $itemTransferm, ?ProductAbstractPackagingStorageTransfer $productAbstractPackagingStorageTransfer): ItemTransfer;
}
