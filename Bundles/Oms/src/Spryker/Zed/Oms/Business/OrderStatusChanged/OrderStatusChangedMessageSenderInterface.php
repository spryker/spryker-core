<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStatusChanged;

interface OrderStatusChangedMessageSenderInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function sendMessage(int $idSalesOrder);
}
