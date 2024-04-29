<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Middleware;

use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class DisableHandleMessagePropelPoolingMiddleware implements MiddlewareInterface
{
    use InstancePoolingTrait;

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param \Symfony\Component\Messenger\Middleware\StackInterface $stack
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (!$envelope->all(ReceivedStamp::class)) {
            return $stack->next()->handle($envelope, $stack);
        }

        $isPropelInstancePoolingEnabled = $this->isInstancePoolingEnabled();
        $this->disableInstancePooling();

        try {
            return $stack->next()->handle($envelope, $stack);
        } finally {
            if ($isPropelInstancePoolingEnabled) {
                $this->enableInstancePooling();
            }
        }
    }
}
