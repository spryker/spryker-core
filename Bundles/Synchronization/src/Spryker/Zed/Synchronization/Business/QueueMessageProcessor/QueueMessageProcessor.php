<?php

namespace Spryker\Zed\Synchronization\Business\QueueMessageProcessor;

use Exception;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Spryker\Zed\Synchronization\Business\Exception\SynchronizationIsNotDefinedException;
use Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface;

class QueueMessageProcessor implements QueueMessageProcessorInterface
{
    protected const WRITE_TYPE = 'write';
    protected const DELETE_TYPE = 'delete';

    /**
     * @var \Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface
     */
    protected $synchronization;

    /**
     * @var \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        SynchronizationToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface $synchronization
     *
     * @return void
     */
    public function setSynchronization(SynchronizationInterface $synchronization): void
    {
        $this->synchronization = $synchronization;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return array
     */
    public function processMessages(array $queueMessageTransfers): array
    {
        if ($this->synchronization === null) {
            throw new SynchronizationIsNotDefinedException('Please define synchronization model by calling setSynchronization method.');
        }

        foreach ($queueMessageTransfers as $key => $queueMessageTransfer) {
            $queueMessageTransfers[$key] = $this->processMessage($queueMessageTransfer);
        }

        return $queueMessageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    protected function processMessage(QueueReceiveMessageTransfer $queueMessageTransfer): QueueReceiveMessageTransfer
    {
        try {
            $messageBody = $this->utilEncodingService->decodeJson($queueMessageTransfer->getQueueMessage()->getBody(), true);

            $this->processMessageWriteType($messageBody, $queueMessageTransfer->getQueueName());
            $this->processMessageDeleteType($messageBody, $queueMessageTransfer->getQueueName());

            $queueMessageTransfer->setAcknowledge(true);
        } catch (Exception $exception) {
            $this->markMessageAsFailed($queueMessageTransfer, $exception->getMessage());
        }

        return $queueMessageTransfer;
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

        $this->synchronization->write($messageBody[static::WRITE_TYPE], $queueName);
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

        $this->synchronization->delete($messageBody[static::DELETE_TYPE], $queueName);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     * @param string $errorMessage
     *
     * @return void
     */
    protected function markMessageAsFailed(QueueReceiveMessageTransfer $queueMessageTransfer, string $errorMessage = '')
    {
        $this->setMessageError($queueMessageTransfer, $errorMessage);
        $queueMessageTransfer->setAcknowledge(false);
        $queueMessageTransfer->setReject(true);
        $queueMessageTransfer->setHasError(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     * @param string $errorMessage
     *
     * @return void
     */
    protected function setMessageError(QueueReceiveMessageTransfer $queueMessageTransfer, string $errorMessage = '')
    {
        $queueMessageBody = $this->utilEncodingService->decodeJson($queueMessageTransfer->getQueueMessage()->getBody(), true);
        $queueMessageBody['errorMessage'] = $errorMessage;
        $queueMessageTransfer->getQueueMessage()->setBody($this->utilEncodingService->encodeJson($queueMessageBody));
    }
}
