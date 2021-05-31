<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SellableItemBatchRequestTransfer;
use Generated\Shared\Transfer\SellableItemBatchResponseTransfer;

interface BatchAvailabilityStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns calculated product concrete availability for provided batch of criteria items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemBatchResponseTransfer $sellableItemBatchResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemBatchResponseTransfer
     */
    public function findItemsAvailabilityForStore(
        SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer,
        SellableItemBatchResponseTransfer $sellableItemBatchResponseTransfer
    ): SellableItemBatchResponseTransfer;
}
