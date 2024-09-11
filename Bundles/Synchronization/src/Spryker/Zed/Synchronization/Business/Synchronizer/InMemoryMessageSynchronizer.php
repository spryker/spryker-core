<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Synchronizer;

use Exception;
use Generated\Shared\Transfer\SynchronizationMessageTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface;
use Throwable;

class InMemoryMessageSynchronizer implements MessageSynchronizerInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const TYPE_WRITE = 'write';

    /**
     * @var string
     */
    protected const TYPE_DELETE = 'delete';

    /**
     * @var array<string, array<string, array<string, list<\Generated\Shared\Transfer\SynchronizationMessageTransfer>>>>
     */
    protected static array $messages = [];

    /**
     * @var \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface
     */
    protected SynchronizationToQueueClientInterface $queueClient;

    /**
     * @var list<\Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface>
     */
    protected array $synchronizationWriters;

    /**
     * @param \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface $queueClient
     * @param list<\Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface> $synchronizationWriters
     */
    public function __construct(SynchronizationToQueueClientInterface $queueClient, array $synchronizationWriters)
    {
        $this->queueClient = $queueClient;
        $this->synchronizationWriters = $synchronizationWriters;
    }

    /**
     * @param \Generated\Shared\Transfer\SynchronizationMessageTransfer $synchronizationMessage
     *
     * @return void
     */
    public function addSynchronizationMessage(SynchronizationMessageTransfer $synchronizationMessage): void
    {
        $operationType = $synchronizationMessage->getOperationType();
        $fallbackQueueName = $synchronizationMessage->getFallbackQueueName();
        $destinationType = $synchronizationMessage->getSyncDestinationType();

        if (!in_array($operationType, [static::TYPE_WRITE, static::TYPE_DELETE], true)) {
            return;
        }

        static::$messages[$destinationType][$fallbackQueueName][$operationType][] = $synchronizationMessage;
    }

    /**
     * @return void
     */
    public function flushSynchronizationMessages(): void
    {
        if (!static::$messages) {
            return;
        }

        foreach (static::$messages as $destinationType => $queues) {
            foreach ($queues as $fallbackQueueName => $synchronizationMessagesGroupedByOperationType) {
                try {
                    $this->synchronizeBulkMessages($destinationType, $synchronizationMessagesGroupedByOperationType);
                } catch (Throwable $exception) {
                    $this->getLogger()->error(
                        sprintf('Exception occurred: %s. Message will be rerouted to the queue: %s', $exception->getMessage(), $fallbackQueueName),
                        ['exception' => $exception],
                    );
                    $this->sendFailedMessagesToQueue($fallbackQueueName, $synchronizationMessagesGroupedByOperationType);
                }
            }
        }
    }

    /**
     * @param string $destinationType
     * @param array<string, list<\Generated\Shared\Transfer\SynchronizationMessageTransfer>> $synchronizationMessagesGroupedByOperationType
     *
     * @return void
     */
    protected function synchronizeBulkMessages(
        string $destinationType,
        array $synchronizationMessagesGroupedByOperationType
    ): void {
        $synchronizationWriter = $this->getSynchronizationWriter($destinationType);

        foreach ($synchronizationMessagesGroupedByOperationType as $operation => $messages) {
            if ($operation === static::TYPE_WRITE) {
                $synchronizationWriter->writeBulk($this->extractMessageData($messages));

                continue;
            }

            if ($operation === static::TYPE_DELETE) {
                $synchronizationWriter->deleteBulk($this->extractMessageData($messages));
            }
        }
    }

    /**
     * @param string $destinationType
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface
     */
    protected function getSynchronizationWriter(string $destinationType): SynchronizationInterface
    {
        foreach ($this->synchronizationWriters as $synchronizationWriter) {
            if ($synchronizationWriter->isDestinationTypeApplicable($destinationType)) {
                return $synchronizationWriter;
            }
        }

        throw new Exception(sprintf(
            'Synchronization for destination type "%s" not found.',
            $destinationType,
        ));
    }

    /**
     * @param string $destinationQueue
     * @param array<string, list<\Generated\Shared\Transfer\SynchronizationMessageTransfer>> $synchronizationMessagesGroupedByOperationType
     *
     * @return void
     */
    protected function sendFailedMessagesToQueue(string $destinationQueue, array $synchronizationMessagesGroupedByOperationType): void
    {
        foreach ($synchronizationMessagesGroupedByOperationType as $messages) {
            $queueMessageTransfers = $this->extractQueueMessageTransfers($messages);
            $this->queueClient->sendMessages($destinationQueue, $queueMessageTransfers);
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\SynchronizationMessageTransfer> $synchronizationMessages
     *
     * @return list<\Generated\Shared\Transfer\QueueSendMessageTransfer>
     */
    protected function extractQueueMessageTransfers(array $synchronizationMessages): array
    {
        $queueMessageTransfers = [];
        foreach ($synchronizationMessages as $synchronizationMessageTransfer) {
            $queueMessageTransfers[] = $synchronizationMessageTransfer->getFallbackQueueMessage();
        }

        return $queueMessageTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\SynchronizationMessageTransfer> $synchronizationMessages
     *
     * @return array<mixed>
     */
    protected function extractMessageData(array $synchronizationMessages): array
    {
        $messageData = [];
        foreach ($synchronizationMessages as $synchronizationMessageTransfer) {
            $messageData[] = $synchronizationMessageTransfer->getData();
        }

        return $messageData;
    }
}
