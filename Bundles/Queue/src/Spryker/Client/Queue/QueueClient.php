<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Queue\QueueFactory getFactory()
 */
class QueueClient extends AbstractClient implements QueueClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return array
     */
    public function createQueue($queueName, array $options = [])
    {
        return $this->getFactory()->createQueueProxy()->createQueue($queueName, $options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer $queueSendMessageTransfer
     *
     * @return void
     */
    public function sendMessage($queueName, QueueSendMessageTransfer $queueSendMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->sendMessage($queueName, $queueSendMessageTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueSendMessageTransfer[] $queueSendMessageTransfers
     *
     * @return void
     */
    public function sendMessages($queueName, array $queueSendMessageTransfers)
    {
        $this->getFactory()->createQueueProxy()->sendMessages($queueName, $queueSendMessageTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $queueName
     * @param int $chunkSize
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function receiveMessages($queueName, $chunkSize = 100, array $options = [])
    {
        return $this->getFactory()->createQueueProxy()->receiveMessages($queueName, $chunkSize, $options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    public function receiveMessage($queueName, array $options = [])
    {
        return $this->getFactory()->createQueueProxy()->receiveMessage($queueName, $options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function acknowledge(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->acknowledge($queueReceiveMessageTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function reject(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->reject($queueReceiveMessageTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return bool
     */
    public function handleError(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        return $this->getFactory()->createQueueProxy()->handleError($queueReceiveMessageTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function purgeQueue($queueName, array $options = [])
    {
        return $this->getFactory()->createQueueProxy()->purgeQueue($queueName, $options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return bool
     */
    public function deleteQueue($queueName, array $options = [])
    {
        return $this->getFactory()->createQueueProxy()->deleteQueue($queueName, $options);
    }
}
