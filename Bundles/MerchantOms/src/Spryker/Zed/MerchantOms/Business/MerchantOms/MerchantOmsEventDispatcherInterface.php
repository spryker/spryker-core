<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\MerchantOms;

use Generated\Shared\Transfer\MerchantOmsEventTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;

interface MerchantOmsEventDispatcherInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderResponseTransfer
     */
    public function dispatchNewMerchantOrderEvent(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     * @param \Generated\Shared\Transfer\MerchantOmsEventTransfer $merchantOmsEventTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    public function dispatchMerchantOrderItemEvent(
        MerchantOrderItemTransfer $merchantOrderItemTransfer,
        MerchantOmsEventTransfer $merchantOmsEventTransfer
    ): MerchantOrderItemResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantOmsEventTransfer $merchantOmsEventTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemResponseTransfer
     */
    public function dispatchMerchantOrderItemsEvent(
        MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer,
        MerchantOmsEventTransfer $merchantOmsEventTransfer
    ): MerchantOrderItemResponseTransfer;
}
