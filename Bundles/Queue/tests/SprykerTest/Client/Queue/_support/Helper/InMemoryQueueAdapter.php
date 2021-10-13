<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Queue\Helper;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Ramsey\Uuid\Uuid;

class InMemoryQueueAdapter implements InMemoryAdapterInterface
{
    /**
     * Needs to be static because internally each call to the queue creates a new instance but for testing
     * purposes we need to see all messages from all queues inside of the current request.
     *
     * @var array
     */
    protected static $queues = [];

    /**
     * @var array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    protected static $receivedMessages = [];

    /**
     * @var array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    protected static $acknowledgedMessages = [];

    /**
     * @var array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    protected static $rejectedMessages = [];

    /**
     * @var array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    protected static $erroredMessages = [];

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return void
     */
    public function createQueue($queueName, array $options = []): void
    {
        static::$queues[$queueName] = [];
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return void
     */
    public function purgeQueue($queueName, array $options = []): void
    {
        if (isset(static::$queues[$queueName])) {
            static::$queues[$queueName] = [];
        }
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return void
     */
    public function deleteQueue($queueName, array $options = []): void
    {
        if (isset(static::$queues[$queueName])) {
            unset(static::$queues[$queueName]);
        }
    }

    /**
     * @param string $queueName
     * @param int $chunkSize
     * @param array $options
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function receiveMessages($queueName, $chunkSize = 100, array $options = []): array
    {
        if (!isset(static::$queues[$queueName])) {
            return [];
        }

        /** @var array<\Generated\Shared\Transfer\QueueSendMessageTransfer> $queueSendMessageTransfers */
        $queueSendMessageTransfers = array_slice(static::$queues[$queueName], 0, $chunkSize);
        $queueReceiveMessageTransferCollection = [];

        foreach ($queueSendMessageTransfers as $queueSendMessageTransfer) {
            $queueReceiveMessageTransferCollection[] = $this->buildQueueReceiveMessageTransfer($queueSendMessageTransfer, $queueName);
        }

        return $queueReceiveMessageTransferCollection;
    }

    /**
     * @param string $queueName
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    public function receiveMessage($queueName, array $options = [])
    {
        $queueSendMessageTransfer = array_shift(static::$queues[$queueName]);

        return $this->buildQueueReceiveMessageTransfer($queueSendMessageTransfer, $queueName);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer $queueSendMessageTransfer
     * @param string $queueName
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    protected function buildQueueReceiveMessageTransfer(
        QueueSendMessageTransfer $queueSendMessageTransfer,
        string $queueName
    ): QueueReceiveMessageTransfer {
        $queueReceiveMessageTransfer = new QueueReceiveMessageTransfer();
        $queueReceiveMessageTransfer->setQueueMessage($queueSendMessageTransfer);
        $queueReceiveMessageTransfer->setQueueName($queueName);
        $queueReceiveMessageTransfer->setDeliveryTag(Uuid::uuid4()->toString());

        static::$receivedMessages[] = $queueReceiveMessageTransfer;

        return $queueReceiveMessageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function acknowledge(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        static::$acknowledgedMessages[] = $queueReceiveMessageTransfer;
        codecept_debug(sprintf('Message was acknowledged from "%s" queue', $queueReceiveMessageTransfer->getQueueName()));
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function reject(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        static::$rejectedMessages[] = $queueReceiveMessageTransfer;
        codecept_debug(sprintf('Message was rejected from "%s" queue', $queueReceiveMessageTransfer->getQueueName()));
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function handleError(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        static::$erroredMessages[] = $queueReceiveMessageTransfer;
        codecept_debug(sprintf('Ann error occurred for a message of the "%s" queue', $queueReceiveMessageTransfer->getQueueName()));
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer $queueSendMessageTransfer
     *
     * @return void
     */
    public function sendMessage($queueName, QueueSendMessageTransfer $queueSendMessageTransfer)
    {
        static::$queues[$queueName] ?? $this->createQueue($queueName);
        static::$queues[$queueName][] = $queueSendMessageTransfer;
    }

    /**
     * @param string $queueName
     * @param array $queueSendMessageTransfers
     *
     * @return void
     */
    public function sendMessages($queueName, array $queueSendMessageTransfers)
    {
        static::$queues[$queueName] ?? $this->createQueue($queueName);
        static::$queues[$queueName] = array_merge(static::$queues[$queueName], $queueSendMessageTransfers);
    }

    /**
     * @param string $queueName
     *
     * @return int|null
     */
    public function getMessageCountInQueue(string $queueName): ?int
    {
        if (!isset(static::$queues[$queueName])) {
            return null;
        }

        return count(static::$queues[$queueName]);
    }

    /**
     * Returns current state of the in-memory queue.
     *
     * @return array
     */
    public function getAll(): array
    {
        return [
            'queues' => static::$queues,
            'receivedMessages' => static::$receivedMessages,
            'acknowledgedMessages' => static::$acknowledgedMessages,
            'rejectedMessages' => static::$rejectedMessages,
            'erroredMessages' => static::$erroredMessages,
        ];
    }

    /**
     * @return void
     */
    public function cleanAll(): void
    {
        static::$queues = [];
        static::$receivedMessages = [];
        static::$acknowledgedMessages = [];
        static::$rejectedMessages = [];
        static::$erroredMessages = [];
    }

    /**
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function getReceivedMessages(): array
    {
        return static::$receivedMessages;
    }

    /**
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function getAcknowledgedMessages(): array
    {
        return static::$acknowledgedMessages;
    }

    /**
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function getRejectedMessages(): array
    {
        return static::$rejectedMessages;
    }

    /**
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function getErroredMessages(): array
    {
        return static::$erroredMessages;
    }
}
