<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\Expander;

use Generated\Shared\Transfer\MerchantOrderTransfer;

interface MerchantOrderExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function expandMerchantOrderWithMerchantOmsData(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function expandMerchantOrderItemsWithStateHistory(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer;
}
