<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Checkout\Dependency;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsInterface;
use Spryker\Zed\Oms\Business\OmsFacade as SprykerOmsFacade;

class OmsFacade extends SprykerOmsFacade implements CheckoutToOmsInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function selectProcess(OrderTransfer $orderTransfer)
    {
        return 'CheckoutTest01';
    }

}
