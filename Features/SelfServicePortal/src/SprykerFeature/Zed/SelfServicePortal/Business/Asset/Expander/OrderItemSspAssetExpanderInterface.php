<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderItemSspAssetExpanderInterface
{
    public function expandOrderItemsWithSspAssets(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandItemsWithSspAssets(array $itemTransfers): array;
}
