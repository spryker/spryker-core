<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Business\Expander;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface ItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderItemsWithMerchantStockAddressSplitByStockAvailability(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function expandCalculableObjectItemsWithMerchantStockAddressSplitByStockAvailability(
        CalculableObjectTransfer $quoteTransfer
    ): CalculableObjectTransfer;
}
