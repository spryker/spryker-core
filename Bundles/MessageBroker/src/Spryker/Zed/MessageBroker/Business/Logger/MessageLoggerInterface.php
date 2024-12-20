<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Logger;

use Symfony\Component\Messenger\Envelope;

interface MessageLoggerInterface
{
    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param float $startMicrotime
     *
     * @return void
     */
    public function logInfo(Envelope $envelope, float $startMicrotime): void;

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param float $startMicrotime
     * @param string $errorMessage
     *
     * @return void
     */
    public function logError(Envelope $envelope, float $startMicrotime, string $errorMessage): void;
}
