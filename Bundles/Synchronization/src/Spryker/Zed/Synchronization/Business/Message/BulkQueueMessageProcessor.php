<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Message;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface;
use Throwable;

class BulkQueueMessageProcessor implements QueueMessageProcessorInterface
{
    /**
     * @var string
     */
    protected const TYPE_WRITE = 'write';

    /**
     * @var string
     */
    protected const TYPE_DELETE = 'delete';

    /**
     * @var string
     */
    protected const KEY_MESSAGE_BODY = 'message';

    /**
     * @var string
     */
    protected const KEY_TRANSFER = 'transfer';

    /**
     * @var \Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface
     */
    protected $synchronization;

    /**
     * @var \Spryker\Zed\Synchronization\Business\Message\QueueMessageHelperInterface
     */
    protected $queueMessageHelper;

    /**
     * @param \Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface $synchronization
     * @param \Spryker\Zed\Synchronization\Business\Message\QueueMessageHelperInterface $queueMessageHelper
     */
    public function __construct(SynchronizationInterface $synchronization, QueueMessageHelperInterface $queueMessageHelper)
    {
        $this->synchronization = $synchronization;
        $this->queueMessageHelper = $queueMessageHelper;
    }

    /**
     * @param array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer> $queueMessageTransfers
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function processMessages(array $queueMessageTransfers): array
    {
        $writeMessagesByQueue = [];
        $deleteMessagesByQueue = [];

        foreach ($queueMessageTransfers as $queueMessageTransfer) {
            $deliveryTag = $queueMessageTransfer->getDeliveryTag();
            $messageBody = $this->extractQuoteMessageBody($queueMessageTransfer);
            $queueName = $queueMessageTransfer->getQueueName();

            if (isset($messageBody[static::TYPE_WRITE])) {
                $writeMessagesByQueue[$queueName][static::KEY_MESSAGE_BODY][$deliveryTag] = $messageBody[static::TYPE_WRITE];
                $writeMessagesByQueue[$queueName][static::KEY_TRANSFER][$deliveryTag] = $queueMessageTransfer;
            }

            if (isset($messageBody[static::TYPE_DELETE])) {
                $deleteMessagesByQueue[$queueName][static::KEY_MESSAGE_BODY][$deliveryTag] = $messageBody[static::TYPE_DELETE];
                $deleteMessagesByQueue[$queueName][static::KEY_TRANSFER][$deliveryTag] = $queueMessageTransfer;
            }
        }

        return array_merge(
            $this->runBulkWrite($writeMessagesByQueue),
            $this->runBulkDelete($deleteMessagesByQueue),
        );
    }

    /**
     * @param array $writeMessagesByQueue
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    protected function runBulkWrite(array $writeMessagesByQueue): array
    {
        $resultQueueMessageTransfers = [];

        foreach ($writeMessagesByQueue as $queueName => $writeMessages) {
            $messageBodies = $writeMessages[static::KEY_MESSAGE_BODY];
            $queueMessageTransfers = $writeMessages[static::KEY_TRANSFER];

            try {
                $this->synchronization->writeBulk($messageBodies);
                $processedMessageTransfers = $this->markEachMessageChunkAsAcknowledged($queueMessageTransfers);
            } catch (Throwable $exception) {
                $processedMessageTransfers = $this->restoreQueueMessageBodies($queueMessageTransfers, $messageBodies, static::TYPE_WRITE);
                $processedMessageTransfers = $this->markEachMessageChunkAsFailed($processedMessageTransfers, $exception->getMessage());
            }

            $resultQueueMessageTransfers = array_merge($resultQueueMessageTransfers, $processedMessageTransfers);
        }

        return $resultQueueMessageTransfers;
    }

    /**
     * @param array $deleteMessagesByQueue
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    protected function runBulkDelete(array $deleteMessagesByQueue): array
    {
        $resultQueueMessageTransfers = [];

        foreach ($deleteMessagesByQueue as $queueName => $deleteMessages) {
            $messageBodies = $deleteMessages[static::KEY_MESSAGE_BODY];
            $queueMessageTransfers = $deleteMessages[static::KEY_TRANSFER];

            try {
                $this->synchronization->deleteBulk($messageBodies);
                $processedMessageTransfers = $this->markEachMessageChunkAsAcknowledged($queueMessageTransfers);
            } catch (Throwable $exception) {
                $processedMessageTransfers = $this->restoreQueueMessageBodies($queueMessageTransfers, $messageBodies, static::TYPE_DELETE);
                $processedMessageTransfers = $this->markEachMessageChunkAsFailed($processedMessageTransfers, $exception->getMessage());
            }

            $resultQueueMessageTransfers = array_merge($resultQueueMessageTransfers, $processedMessageTransfers);
        }

        return $resultQueueMessageTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer> $queueMessageTransfers
     * @param string $errorMessage
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    protected function markEachMessageChunkAsFailed(array $queueMessageTransfers, string $errorMessage = ''): array
    {
        $markedQueueMessageTransfers = [];
        foreach ($queueMessageTransfers as $queueMessageTransfer) {
            $markedQueueMessageTransfers[] = $this->queueMessageHelper->markMessageAsFailed($queueMessageTransfer, $errorMessage);
        }

        return $markedQueueMessageTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer> $queueMessageTransfers
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    protected function markEachMessageChunkAsAcknowledged(array $queueMessageTransfers): array
    {
        $markedQueueMessageTransfers = [];
        foreach ($queueMessageTransfers as $queueMessageTransfer) {
            $queueMessageTransfer->setAcknowledge(true);

            $markedQueueMessageTransfers[] = $queueMessageTransfer;
        }

        return $markedQueueMessageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     *
     * @return array
     */
    protected function extractQuoteMessageBody(QueueReceiveMessageTransfer $queueMessageTransfer): array
    {
        $queueMessage = $queueMessageTransfer->getQueueMessage();
        $messageBody = $this->queueMessageHelper->decodeJson($queueMessage->getBody(), true);
        $queueMessage->setBody(null);

        return $messageBody;
    }

    /**
     * @param array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer> $queueMessageTransfers
     * @param array $messageBodies
     * @param string $type
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    protected function restoreQueueMessageBodies(array $queueMessageTransfers, array $messageBodies, string $type): array
    {
        $restoredQueueMessageTransfers = [];

        foreach ($messageBodies as $deliveryTag => $messageBody) {
            $queueMessageTransfer = $queueMessageTransfers[$deliveryTag] ?? null;

            if (!$queueMessageTransfer || (int)$queueMessageTransfer->getDeliveryTag() !== $deliveryTag) {
                continue;
            }

            $messageBody = $this->queueMessageHelper->encodeJson([$type => $messageBody]);
            $queueMessageTransfer->getQueueMessage()->setBody($messageBody);
            $restoredQueueMessageTransfers[] = $queueMessageTransfer;
        }

        return $restoredQueueMessageTransfers;
    }
}
