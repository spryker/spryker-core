<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Payone\Communication\PayoneDependencyContainer;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class RefundIsApprovedPlugin extends AbstractPlugin
{
    const NAME = 'RefundIsApprovedPlugin';

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createPayoneFacade()->isRefundApproved($orderTransfer);
    }

}
