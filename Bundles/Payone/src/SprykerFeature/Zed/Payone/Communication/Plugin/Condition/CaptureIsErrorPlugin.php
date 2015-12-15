<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Condition;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Payone\Business\PayoneFacade;

/**
 * @method PayoneFacade getFacade()
 */
class CaptureIsErrorPlugin extends AbstractPlugin
{

    const NAME = 'CaptureIsErrorPlugin';

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function callFacade(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->isCaptureError($orderTransfer);
    }

}
