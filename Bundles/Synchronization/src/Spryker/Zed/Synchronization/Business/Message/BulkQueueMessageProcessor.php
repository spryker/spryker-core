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
    protected const TYPE_WRITE = 'write';
    protected const TYPE_DELETE = 'delete';

    protected const KEY_MESSAGE_BODY = 'message';
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
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processMessages(array $queueMessageTransfers): array
    {
        $writeMessagesByQueue = [];
        $deleteMessagesByQueue = [];

        foreach ($queueMessageTransfers as $key => $queueMessageTransfer) {
            $messageBody = $this->extractQuoteMessageBody($queueMessageTransfer);
            $queueName = $queueMessageTransfer->getQueueName();

            if (isset($messageBody[static::TYPE_WRITE])) {
                $writeMessagesByQueue[$queueName][static::KEY_MESSAGE_BODY][$key] = $messageBody[static::TYPE_WRITE];
                $writeMessagesByQueue[$queueName][static::KEY_TRANSFER][$key] = $queueMessageTransfer;
            }

            if (isset($messageBody[static::TYPE_DELETE])) {
                $deleteMessagesByQueue[$queueName][static::KEY_MESSAGE_BODY][$key] = $messageBody[static::TYPE_DELETE];
                $deleteMessagesByQueue[$queueName][static::KEY_TRANSFER][$key] = $queueMessageTransfer;
            }
        }

        return array_merge(
            $this->runBulkWrite($writeMessagesByQueue),
            $this->runBulkDelete($deleteMessagesByQueue)
        );
    }

    /**
     * @param array $writeMessagesByQueue
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
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
                $processedMessageTransfers = $this->processFailedQueueMessages($queueMessageTransfers, $messageBodies, $exception->getMessage());
            }

            $resultQueueMessageTransfers = array_merge($resultQueueMessageTransfers, $processedMessageTransfers);
        }

        return $resultQueueMessageTransfers;
    }

    /**
     * @param array $deleteMessagesByQueue
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
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
                $processedMessageTransfers = $this->processFailedQueueMessages($queueMessageTransfers, $messageBodies, $exception->getMessage());
            }

            $resultQueueMessageTransfers = array_merge($resultQueueMessageTransfers, $processedMessageTransfers);
        }

        return $resultQueueMessageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
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
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
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
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     * @param array $messageBodies
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    protected function processFailedQueueMessages(array $queueMessageTransfers, array $messageBodies, string $errorMessage): array
    {
        $processedMessageTransfers = $this->restoreQueueMessageBodies($queueMessageTransfers, $messageBodies);

        return $this->markEachMessageChunkAsFailed($processedMessageTransfers, $errorMessage);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     * @param array $messageBodies
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    protected function restoreQueueMessageBodies(array $queueMessageTransfers, array &$messageBodies): array
    {
        $restoredQueueMessageTransfers = [];
        foreach ($messageBodies as $key => $messageBody) {
            if (isset($queueMessageTransfers[$key])) {
                $messageBody = $this->queueMessageHelper->encodeJson($messageBody);
                $queueMessageTransfers[$key]->getQueueMessage()->setBody($messageBody);
                $restoredQueueMessageTransfers[] = $queueMessageTransfers[$key];
            }
        }

        return $restoredQueueMessageTransfers;
    }
}
