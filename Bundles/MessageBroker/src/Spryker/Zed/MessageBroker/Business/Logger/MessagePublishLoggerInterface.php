<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Logger;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface MessagePublishLoggerInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     * @param float $startMicrotime
     *
     * @return void
     */
    public function logInfo(TransferInterface $messageTransfer, float $startMicrotime): void;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     * @param float $startMicrotime
     * @param string $message
     *
     * @return void
     */
    public function logError(TransferInterface $messageTransfer, float $startMicrotime, string $message): void;
}
