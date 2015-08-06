<?php

namespace Functional\SprykerFeature\Zed\Sales\Business\Dependency;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Oms\Business\OmsFacade as SprykerOmsFacade;
use SprykerFeature\Zed\Sales\Dependency\Facade\SalesToOmsInterface;

class OmsFacade extends SprykerOmsFacade implements SalesToOmsInterface
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
