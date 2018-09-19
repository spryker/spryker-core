<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Message;

use Exception;
use Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface;

class BulkQueueMessageProcessor implements QueueMessageProcessorInterface
{
    protected const WRITE_TYPE = 'write';
    protected const DELETE_TYPE = 'delete';

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

        foreach ($queueMessageTransfers as $queueMessageTransfer) {
            $messageBody = $this->queueMessageHelper->decodeJson($queueMessageTransfer->getQueueMessage()->getBody(), true);
            $queueName = $queueMessageTransfer->getQueueName();

            if (isset($messageBody[static::WRITE_TYPE])) {
                $writeMessagesByQueue[$queueName][static::KEY_MESSAGE_BODY][] = $messageBody[static::WRITE_TYPE];
                $writeMessagesByQueue[$queueName][static::KEY_TRANSFER][] = $queueMessageTransfer;
            }

            if (isset($messageBody[static::DELETE_TYPE])) {
                $deleteMessagesByQueue[$queueName][static::KEY_MESSAGE_BODY][] = $messageBody[static::DELETE_TYPE];
                $deleteMessagesByQueue[$queueName][static::KEY_TRANSFER][] = $queueMessageTransfer;
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
        $processedQueueMessageTransfers = [];

        foreach ($writeMessagesByQueue as $queueName => $writeMessages) {
            $messageBodies = $writeMessages[static::KEY_MESSAGE_BODY];
            $queueMessageTransfers = $writeMessages[static::KEY_TRANSFER];

            try {
                $this->synchronization->writeBulk($messageBodies, $queueName);
                $this->markEachMessageAsAcknowledged($queueMessageTransfers);
            } catch (Exception $exception) {
                $this->markEachMessageAsFailed($queueMessageTransfers, $exception->getMessage());
            }

            $processedQueueMessageTransfers = array_merge($processedQueueMessageTransfers, $queueMessageTransfers);
        }

        return $processedQueueMessageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $deleteMessagesByQueue
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    protected function runBulkDelete(array $deleteMessagesByQueue): array
    {
        $processedQueueMessageTransfers = [];

        foreach ($deleteMessagesByQueue as $queueName => $deleteMessages) {
            $messageBodies = $deleteMessages[static::KEY_MESSAGE_BODY];
            $queueMessageTransfers = $deleteMessages[static::KEY_TRANSFER];

            try {
                $this->synchronization->deleteBulk($messageBodies, $queueName);
                $this->markEachMessageAsAcknowledged($queueMessageTransfers);
            } catch (Exception $exception) {
                $this->markEachMessageAsFailed($queueMessageTransfers, $exception->getMessage());
            }

            $processedQueueMessageTransfers = array_merge($processedQueueMessageTransfers, $queueMessageTransfers);
        }

        return $processedQueueMessageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     * @param string $errorMessage
     *
     * @return void
     */
    protected function markEachMessageAsFailed(array $queueMessageTransfers, string $errorMessage = ''): void
    {
        foreach ($queueMessageTransfers as &$queueMessageTransfer) {
            $this->queueMessageHelper->markMessageAsFailed($queueMessageTransfer, $errorMessage);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return void
     */
    protected function markEachMessageAsAcknowledged(array $queueMessageTransfers): void
    {
        foreach ($queueMessageTransfers as &$queueMessageTransfer) {
            $queueMessageTransfer->setAcknowledge(true);
        }
    }
}
