<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Middleware;

use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\MessageBroker\Business\MessageChannelProvider\MessageChannelProviderInterface;
use Spryker\Zed\MessageBroker\Business\Receiver\Stamp\ChannelNameStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class AddChannelNameStampMiddleware implements MiddlewareInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\MessageBroker\Business\MessageChannelProvider\MessageChannelProviderInterface
     */
    protected MessageChannelProviderInterface $messageChannelProvider;

    /**
     * @param \Spryker\Zed\MessageBroker\Business\MessageChannelProvider\MessageChannelProviderInterface $messageChannelProvider
     */
    public function __construct(MessageChannelProviderInterface $messageChannelProvider)
    {
        $this->messageChannelProvider = $messageChannelProvider;
    }

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param \Symfony\Component\Messenger\Middleware\StackInterface $stack
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $channel = $this->messageChannelProvider->findChannelForMessage($envelope);

        if (!$channel) {
            $this->getLogger()->warning(
                'Could not map message to channel name',
                ['messageName' => get_class($envelope->getMessage())],
            );

            return $envelope;
        }

        $envelope = $envelope->with(new ChannelNameStamp($channel));

        return $stack->next()->handle($envelope, $stack);
    }
}
