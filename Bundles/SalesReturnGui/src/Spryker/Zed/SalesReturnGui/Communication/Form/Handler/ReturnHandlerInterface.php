<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Form\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;

interface ReturnHandlerInterface
{
    /**
     * @param array $returnItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(array $returnItems, OrderTransfer $orderTransfer): ReturnResponseTransfer;
}
