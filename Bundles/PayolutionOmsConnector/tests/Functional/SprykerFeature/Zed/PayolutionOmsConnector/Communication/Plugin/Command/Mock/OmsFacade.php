<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\PayolutionOmsConnector\Communication\Plugin\Command\Mock;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Dependency\Facade\CheckoutToOmsInterface;
use SprykerFeature\Zed\Oms\Business\OmsFacade as SprykerOmsFacade;

class OmsFacade extends SprykerOmsFacade implements CheckoutToOmsInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function selectProcess(OrderTransfer $orderTransfer)
    {
        return 'PayolutionPayment01';
    }

}
