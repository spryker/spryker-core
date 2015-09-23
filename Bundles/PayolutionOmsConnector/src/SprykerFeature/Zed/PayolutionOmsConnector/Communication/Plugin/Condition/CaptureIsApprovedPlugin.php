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
class CaptureIsApprovedPlugin extends AbstractPlugin
{

    const NAME = 'CapturePlugin';

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getDependencyContainer()->createPayolutionFacade()->isCaptureApproved($orderTransfer);
    }

}
