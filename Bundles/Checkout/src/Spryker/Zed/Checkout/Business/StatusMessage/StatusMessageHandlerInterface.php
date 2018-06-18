<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\StatusMessage;

use Generated\Shared\Transfer\MessageTransfer;

interface StatusMessageHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return void
     */
    public function addErrorMessage(MessageTransfer $messageTransfer): void;
}
