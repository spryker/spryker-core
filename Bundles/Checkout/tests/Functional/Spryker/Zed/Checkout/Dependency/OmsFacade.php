<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Checkout\Dependency;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsInterface;
use Spryker\Zed\Oms\Business\OmsFacade as SprykerOmsFacade;

class OmsFacade extends SprykerOmsFacade implements CheckoutToOmsInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function selectProcess(OrderTransfer $orderTransfer)
    {
        return 'CheckoutTest01';
    }

}
