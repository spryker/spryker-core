<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Order;

use Generated\Shared\Transfer\QuoteTransfer;

interface OrderManagerInterface
{

    /**
     * @param QuoteTransfer $quoteTransger
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransger);

}
