<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnTransfer;

interface SalesReturnEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function updateOrderItem(ItemTransfer $itemTransfer): ItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function createReturn(ReturnTransfer $returnTransfer): ReturnTransfer;

    /**
     * @param \Generated\Shared\Transfer\ReturnItemTransfer $returnItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnItemTransfer
     */
    public function createReturnItem(ReturnItemTransfer $returnItemTransfer): ReturnItemTransfer;
}
