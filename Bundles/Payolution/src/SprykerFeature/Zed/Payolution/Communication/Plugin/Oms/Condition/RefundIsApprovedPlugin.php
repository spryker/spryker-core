<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Communication\Plugin\Oms\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Payolution\Business\PayolutionFacade;

/**
 * @method PayolutionFacade getFacade()
 */
class RefundIsApprovedPlugin extends AbstractCheckPlugin
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->isRefundApproved($orderTransfer);
    }

}
