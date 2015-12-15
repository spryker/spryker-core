<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Oms\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Payolution\Business\PayolutionFacade;

/**
 * @method PayolutionFacade getFacade()
 */
class IsReversalApprovedPlugin extends AbstractCheckPlugin
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->isReversalApproved($orderTransfer);
    }

}
