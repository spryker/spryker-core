<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Message;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface;
use Throwable;

class QueueMessageProcessor implements QueueMessageProcessorInterface
{
    protected const TYPE_WRITE = 'write';
    protected const TYPE_DELETE = 'delete';

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
     * @return array
     */
    public function processMessages(array $queueMessageTransfers): array
    {
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
            $messageBody = $this->queueMessageHelper->decodeJson($queueMessageTransfer->getQueueMessage()->getBody(), true);

            $this->processMessageWriteType($messageBody, $queueMessageTransfer->getQueueName());
            $this->processMessageDeleteType($messageBody, $queueMessageTransfer->getQueueName());

            $queueMessageTransfer->setAcknowledge(true);
        } catch (Throwable $exception) {
            $this->queueMessageHelper->markMessageAsFailed($queueMessageTransfer, $exception->getMessage());
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
        if (!isset($messageBody[static::TYPE_WRITE])) {
            return;
        }

        $this->synchronization->write($messageBody[static::TYPE_WRITE], $queueName);
    }

    /**
     * @param array $messageBody
     * @param string $queueName
     *
     * @return void
     */
    protected function processMessageDeleteType(array $messageBody, string $queueName): void
    {
        if (!isset($messageBody[static::TYPE_DELETE])) {
            return;
        }

        $this->synchronization->delete($messageBody[static::TYPE_DELETE], $queueName);
    }
}
