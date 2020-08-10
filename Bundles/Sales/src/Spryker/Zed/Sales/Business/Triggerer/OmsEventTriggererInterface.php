<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Triggerer;

use Generated\Shared\Transfer\OrderTransfer;

interface OmsEventTriggererInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function triggerOrderItemsCancelEvent(OrderTransfer $orderTransfer): void;
}
