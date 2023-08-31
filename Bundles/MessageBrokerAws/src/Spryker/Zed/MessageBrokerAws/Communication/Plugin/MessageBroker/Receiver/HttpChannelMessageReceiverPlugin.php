<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBroker\Receiver;

use Exception;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Receiver\QueueReceiverInterface;

/**
 * @method \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig getConfig()
 * @method \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface getFacade()
 */
class HttpChannelMessageReceiverPlugin extends AbstractPlugin implements MessageReceiverPluginInterface, QueueReceiverInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getTransportName(): string
    {
        return MessageBrokerAwsConfig::HTTP_CHANNEL_TRANSPORT;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string> $queueNames
     *
     * @return list<\Symfony\Component\Messenger\Envelope>
     */
    public function getFromQueues(array $queueNames): iterable
    {
        foreach ($queueNames as $channelName) {
            yield from $this->getFacade()->getMessagesFromHttpChannel($channelName);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     *
     * @api
     *
     * @return list<\Symfony\Component\Messenger\Envelope>
     */
    public function get(): iterable
    {
        throw new Exception(sprintf('Since we are using channels we can only get messages through the "%s" interface.', QueueReceiverInterface::class));
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function ack(Envelope $envelope): void
    {
        $this->getFacade()->ackMessageFromHttpChannel($envelope);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function reject(Envelope $envelope): void
    {
        $this->getFacade()->rejectMessageFromHttpChannel($envelope);
    }
}
