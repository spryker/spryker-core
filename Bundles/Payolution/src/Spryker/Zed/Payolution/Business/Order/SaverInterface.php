<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Business\Order;

use Generated\Shared\Transfer\QuoteTransfer;

interface SaverInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer);

}
