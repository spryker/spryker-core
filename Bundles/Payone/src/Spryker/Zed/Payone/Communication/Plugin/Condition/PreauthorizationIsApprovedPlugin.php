<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Payone\Business\PayoneFacade;
use Spryker\Zed\Payone\Communication\PayoneCommunicationFactory;

/**
 * @method PayoneFacade getFacade()
 * @method PayoneCommunicationFactory getFactory()
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
