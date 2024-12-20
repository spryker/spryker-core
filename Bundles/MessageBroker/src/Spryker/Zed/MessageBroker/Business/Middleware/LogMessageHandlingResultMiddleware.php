<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Middleware;

use Spryker\Zed\MessageBroker\Business\Logger\MessageLoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Throwable;

class LogMessageHandlingResultMiddleware implements MiddlewareInterface
{
    /**
     * @param \Spryker\Zed\MessageBroker\Business\Logger\MessageLoggerInterface $messagePublishLogger
     */
    protected MessageLoggerInterface $messagePublishLogger;

    /**
     * @param \Spryker\Zed\MessageBroker\Business\Logger\MessageLoggerInterface $messagePublishLogger
     */
    public function __construct(MessageLoggerInterface $messagePublishLogger)
    {
        $this->messagePublishLogger = $messagePublishLogger;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param \Symfony\Component\Messenger\Middleware\StackInterface $stack
     *
     * @throws \Throwable
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $startMicrotime = microtime(true);

        try {
            $envelope = $stack->next()->handle($envelope, $stack);
            $this->messagePublishLogger->logInfo($envelope, $startMicrotime);

            return $envelope;
        } catch (Throwable $exception) {
            $this->messagePublishLogger->logError($envelope, $startMicrotime, $exception->getMessage());

            throw $exception;
        }
    }
}
