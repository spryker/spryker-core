<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Oms\Condition;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * @method \Spryker\Zed\Payolution\Business\PayolutionFacade getFacade()
 */
class IsCaptureApprovedPlugin extends AbstractCheckPlugin
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->isCaptureApproved($orderTransfer);
    }

}
