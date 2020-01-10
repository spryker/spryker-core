<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OrderTransfer;

interface UniqueOrderItemsExpanderPluginInterface
{
    /**
     * Specification:
     * - This plugin stack gets executed to display a list of unique order items.
     * - Expands provided array of ItemTransfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expand(array $itemsTransfers, OrderTransfer $orderTransfer): array;
}
