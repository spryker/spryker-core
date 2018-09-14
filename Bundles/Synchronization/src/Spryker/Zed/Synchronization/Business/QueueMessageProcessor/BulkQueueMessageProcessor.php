<?php

namespace Spryker\Zed\Synchronization\Business\QueueMessageProcessor;

use Exception;

class BulkQueueMessageProcessor extends QueueMessageProcessor
{
    /**
     * @var array
     */
    protected $bulkMessagesToDelete = [];

    /**
     * @var array
     */
    protected $bulkMessagesToWrite = [];

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return array
     */
    public function processMessages(array $queueMessageTransfers): array
    {
        $queueMessageTransfers = parent::processMessages($queueMessageTransfers);

        return $this->runBulkSynchronization($queueMessageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return array
     */
    protected function runBulkSynchronization(array $queueMessageTransfers): array
    {
        try {
            foreach ($this->bulkMessagesToWrite as $queueName => $bulkMessagesToWrite) {
                $this->synchronization->writeBulk($bulkMessagesToWrite, $queueName);
            }

            foreach ($this->bulkMessagesToDelete as $queueName => $bulkMessagesToDelete) {
                $this->synchronization->deleteBulk($bulkMessagesToDelete, $queueName);
            }
        } catch (Exception $exception) {
            return $this->markEachMessageAsFailed($queueMessageTransfers, $exception->getMessage());
        }

        return $queueMessageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @param string $errorMessage
     *
     * @return array
     */
    protected function markEachMessageAsFailed(array $queueMessageTransfers, string $errorMessage = ''): array
    {
        foreach ($queueMessageTransfers as &$queueMessageTransfer) {
            $this->markMessageAsFailed($queueMessageTransfer, $errorMessage);
        }

        return $queueMessageTransfers;
    }

    /**
     * @param array $messageBody
     * @param string $queueName
     *
     * @return void
     */
    protected function processMessageWriteType(array $messageBody, string $queueName): void
    {
        if (!isset($messageBody[static::WRITE_TYPE])) {
            return;
        }

        $this->bulkMessagesToWrite[$queueName][] = $messageBody[static::WRITE_TYPE];
    }

    /**
     * @param array $messageBody
     * @param string $queueName
     *
     * @return void
     */
    protected function processMessageDeleteType(array $messageBody, string $queueName): void
    {
        if (!isset($messageBody[static::DELETE_TYPE])) {
            return;
        }

        $this->bulkMessagesToDelete[$queueName][] = $messageBody[static::DELETE_TYPE];
    }
}
