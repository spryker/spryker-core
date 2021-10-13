<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Queue\Helper;

use Spryker\Client\Queue\Model\Adapter\AdapterInterface;

interface InMemoryAdapterInterface extends AdapterInterface
{
    /**
     * Returns count of messages in a queue or null when queue does not exists.
     *
     * @param string $queueName
     *
     * @return int|null
     */
    public function getMessageCountInQueue(string $queueName): ?int;

    /**
     * Returns all queues.
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Removes all in-memory queues.
     *
     * @return void
     */
    public function cleanAll(): void;

    /**
     * Returns all received messages.
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function getReceivedMessages(): array;

    /**
     * Returns all acknowledged messages.
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function getAcknowledgedMessages(): array;

    /**
     * Returns all rejected messages.
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function getRejectedMessages(): array;

    /**
     * Returns all errored messages.
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function getErroredMessages(): array;
}
