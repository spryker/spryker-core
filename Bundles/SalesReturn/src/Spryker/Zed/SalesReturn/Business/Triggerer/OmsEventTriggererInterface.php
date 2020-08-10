<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Triggerer;

use Generated\Shared\Transfer\ReturnTransfer;

interface OmsEventTriggererInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return void
     */
    public function triggerOrderItemsReturnEvent(ReturnTransfer $returnTransfer): void;
}
