<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business;

use Symfony\Component\Messenger\Envelope;

interface MessageBrokerAwsFacadeInterface
{
    /**
     * Specification:
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface::sendMessageToHttpChannel()} instead.
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope;

    /**
     * Specification:
     * - Generates an HTTP endpoint by utilizing the HTTP channel sender's base URL and the channel name from the envelope.
     * - Sends the envelope by utilizing the previously generated endpoint.
     * - Throws `MessageValidationFailedException` in case of invalid envelope.
     * - Adds `SenderClientStamp` in case of successful sending.
     *
     * @api
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Spryker\Zed\MessageBrokerAws\Business\Exception\MessageValidationFailedException
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function sendMessageToHttpChannel(Envelope $envelope): Envelope;

    /**
     * Specification:
     * - Receives messages from SQS.
     * - Adds `ChannelNameStamp` in Envelop.
     * - Returns a generator to get an unlimited number of Envelopes.
     * - If a received message cannot be decoded the error message is logged.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface::getMessagesFromHttpChannel()} instead.
     *
     * @param string $channelName
     *
     * @return list<\Symfony\Component\Messenger\Envelope>
     */
    public function getSqs(string $channelName): iterable;

    /**
     * Specification:
     * - Generates an HTTP endpoint by utilizing the HTTP channel receiver's base URL and the channel name.
     * - Receives a list of envelopes by utilizing the previously generated endpoint.
     * - If a received message cannot be decoded, an error message is logged.
     *
     * @api
     *
     * @param string $channelName
     *
     * @return array<\Symfony\Component\Messenger\Envelope>
     */
    public function getMessagesFromHttpChannel(string $channelName): iterable;

    /**
     * Specification:
     * - Generates an HTTP endpoint by utilizing the HTTP channel receiver's base URL and the channel name from the envelope.
     * - Acknowledges the message by utilizing the previously generated endpoint.
     * - Throws `MessageValidationFailedException` in case of invalid envelope.
     *
     * @api
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function ackMessageFromHttpChannel(Envelope $envelope): void;

    /**
     * Specification:
     * - Generates an HTTP endpoint by utilizing the HTTP channel receiver's base URL and the channel name from the envelope.
     * - Removes the message by utilizing the previously generated endpoint.
     * - Throws `MessageValidationFailedException` in case of invalid envelope.
     *
     * @api
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function rejectMessageFromHttpChannel(Envelope $envelope): void;

    /**
     * Specification:
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface::ackMessageFromHttpChannel()} instead.
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function ack(Envelope $envelope): void;

    /**
     * Specification:
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface::rejectMessageFromHttpChannel()} instead.
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function reject(Envelope $envelope): void;

    /**
     * Specification:
     * - Creates queues in the configured AWS SQS service.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return void
     */
    public function createQueues(): void;

    /**
     * Specification:
     * - Creates topics in the configured AWS SNS service.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return void
     */
    public function createTopics(): void;

    /**
     * Specification:
     * - Subscribes queues in the configured AWS SQS service to AWS SNS topic.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return void
     */
    public function subscribeSqsToSns(): void;
}
