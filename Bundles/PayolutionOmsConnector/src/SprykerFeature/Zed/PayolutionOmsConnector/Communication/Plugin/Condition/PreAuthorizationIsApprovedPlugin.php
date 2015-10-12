<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\PayolutionOmsConnector\Communication\Plugin\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\PayolutionOmsConnector\Communication\PayolutionOmsConnectorDependencyContainer;

/**
 * @method PayolutionOmsConnectorDependencyContainer getDependencyContainer()
 */
class PreAuthorizationIsApprovedPlugin extends CheckAbstractPlugin
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createPayolutionFacade()->isPreAuthorizationApproved($orderTransfer);
    }

}
