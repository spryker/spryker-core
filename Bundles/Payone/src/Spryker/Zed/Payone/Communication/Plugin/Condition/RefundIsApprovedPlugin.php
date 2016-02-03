<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Condition;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * @method \Spryker\Zed\Payone\Business\PayoneFacade getFacade()
 * @method \Spryker\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 */
class RefundIsApprovedPlugin extends AbstractPlugin
{

    const NAME = 'RefundIsApprovedPlugin';

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
