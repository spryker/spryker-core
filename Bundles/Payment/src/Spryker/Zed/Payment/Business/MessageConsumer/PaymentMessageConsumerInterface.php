<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\MessageConsumer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface PaymentMessageConsumerInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $messageTransfer
     *
     * @return void
     */
    public function consumePaymentMessage(AbstractTransfer $messageTransfer): void;
}
