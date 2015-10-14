<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Communication\Plugin\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Payone\Business\PayoneFacade;

/**
 * @method PayoneFacade getFacade()
 */
class PreauthorizationIsApprovedPlugin extends AbstractPlugin
{

    const NAME = 'PreauthorizationIsApprovedPlugin';

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->isPreauthorizationApproved($orderTransfer);
    }

}
