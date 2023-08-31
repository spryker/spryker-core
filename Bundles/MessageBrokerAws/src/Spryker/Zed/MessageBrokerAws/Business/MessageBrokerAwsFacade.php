<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Messenger\Envelope;

/**
 * @method \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsBusinessFactory getFactory()
 */
class MessageBrokerAwsFacade extends AbstractFacade implements MessageBrokerAwsFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacade::sendMessageToHttpChannel()} instead.
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        return $this->getFactory()->createSender()->send($envelope);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Spryker\Zed\MessageBrokerAws\Business\Exception\MessageValidationFailedException
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function sendMessageToHttpChannel(Envelope $envelope): Envelope
    {
        return $this->getFactory()->createHttpChannelSenderClient()->send($envelope);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacade::getMessagesFromHttpChannel()} instead.
     *
     * @param string $channelName
     *
     * @return array<\Symfony\Component\Messenger\Envelope>
     */
    public function getSqs(string $channelName): iterable
    {
        return $this->getFactory()->createSqsReceiverClient()->get($channelName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $channelName
     *
     * @return array<\Symfony\Component\Messenger\Envelope>
     */
    public function getMessagesFromHttpChannel(string $channelName): iterable
    {
        return $this->getFactory()->createHttpChannelReceiverClient()->get($channelName);
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
    public function ackMessageFromHttpChannel(Envelope $envelope): void
    {
        $this->getFactory()->createHttpChannelReceiverClient()->ack($envelope);
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
    public function rejectMessageFromHttpChannel(Envelope $envelope): void
    {
        $this->getFactory()->createHttpChannelReceiverClient()->reject($envelope);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacade::ackMessageFromHttpChannel()} instead.
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function ack(Envelope $envelope): void
    {
        $this->getFactory()->createReceiver()->ack($envelope);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacade::rejectMessageFromHttpChannel()} instead.
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function reject(Envelope $envelope): void
    {
        $this->getFactory()->createReceiver()->reject($envelope);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return void
     */
    public function createQueues(): void
    {
        $this->getFactory()->createAwsSqsQueuesCreator()->createQueues();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return void
     */
    public function createTopics(): void
    {
        $this->getFactory()->createAwsSnsTopicCreator()->createTopics();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return void
     */
    public function subscribeSqsToSns(): void
    {
        $this->getFactory()->createAwsSqsQueueSubscriber()->subscribeSqsToSns();
    }
}
