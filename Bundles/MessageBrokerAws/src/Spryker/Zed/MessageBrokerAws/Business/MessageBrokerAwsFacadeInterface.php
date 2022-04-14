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
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function send(Envelope $envelope): Envelope;

    /**
     * Specification:
     *
     * @api
     *
     * @param string $channelName
     *
     * @return array<\Symfony\Component\Messenger\Envelope>
     */
    public function getSqs(string $channelName): iterable;

    /**
     * Specification:
     *
     * @api
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
     * @return void
     */
    public function createQueues(): void;

    /**
     * Specification:
     * - Creates topics in the configured AWS SNS service.
     *
     * @api
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
     * @return void
     */
    public function subscribeSqsToSns(): void;
}
