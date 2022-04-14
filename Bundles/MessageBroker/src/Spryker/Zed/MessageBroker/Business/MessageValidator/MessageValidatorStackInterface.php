<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageValidator;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface MessageValidatorStackInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return bool
     */
    public function isValidMessage(TransferInterface $messageTransfer): bool;
}
