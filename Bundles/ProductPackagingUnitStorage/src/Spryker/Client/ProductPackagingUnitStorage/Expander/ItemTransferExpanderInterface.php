<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\Expander;

use Generated\Shared\Transfer\ItemTransfer;

interface ItemTransferExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandWithDefaultPackagingUnit(ItemTransfer $itemTransfer): ItemTransfer;
}
