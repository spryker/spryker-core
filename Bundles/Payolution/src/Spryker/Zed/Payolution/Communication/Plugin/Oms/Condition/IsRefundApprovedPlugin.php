<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Oms\Condition;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * @method \Spryker\Zed\Payolution\Business\PayolutionFacade getFacade()
 */
class IsRefundApprovedPlugin extends AbstractCheckPlugin
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->isRefundApproved($orderTransfer);
    }

}
