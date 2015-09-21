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
class ReAuthorizationIsApprovedPlugin extends AbstractPlugin
{

    const NAME = 'ReAuthorizationPlugin';

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createPayolutionFacade()->isReAuthorizationApproved($orderTransfer);
    }

}
