<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageValidator;

use Generated\Shared\Transfer\MessageSendingContextTransfer;

interface MessageValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageSendingContextTransfer $messageSendingContextTransfer
     *
     * @return bool
     */
    public function isMessageSendable(MessageSendingContextTransfer $messageSendingContextTransfer): bool;
}
