<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Condition;

use Generated\Shared\Transfer\OrderTransfer;

class AuthorizationIsErrorPlugin extends AbstractPlugin
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createPayoneFacade()->isAuthorizationError($orderTransfer);
    }

}
