<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ReturnTransfer;

interface ReturnExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function expandReturn(ReturnTransfer $returnTransfer): ReturnTransfer;

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function expandReturnItemsBeforeCreate(ReturnTransfer $returnTransfer, ArrayObject $itemTransfers): ReturnTransfer;
}
