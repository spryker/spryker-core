<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Generated\Shared\Transfer\QueueMessageTransfer;
use Generated\Shared\Transfer\QueueOptionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Queue\QueueFactory getFactory()
 */
class QueueClient extends AbstractClient implements QueueClientInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueOptionTransfer $queueOptionTransfer
     *
     * @return QueueOptionTransfer
     */
    public function createQueue(QueueOptionTransfer $queueOptionTransfer)
    {
        return $this->getFactory()->createQueueProxy()->createQueue($queueOptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function sendMessage(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->sendMessage($queueMessageTransfer);
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueMessageTransfer[] $queueMessageTransfers
     *
     * @return void
     */
    public function sendMessages($queueName, array $queueMessageTransfers)
    {
        $this->getFactory()->createQueueProxy()->sendMessages($queueName, $queueMessageTransfers);
    }

    /**
     * @param QueueOptionTransfer $queueOptionTransfer
     *
     * @return QueueMessageTransfer[]
     */
    public function receiveMessages(QueueOptionTransfer $queueOptionTransfer)
    {
        return $this->getFactory()->createQueueProxy()->receiveMessages($queueOptionTransfer);
    }

    /**
     * @param QueueOptionTransfer $queueOptionTransfer
     *
     * @return QueueMessageTransfer
     */
    public function receiveMessage(QueueOptionTransfer $queueOptionTransfer)
    {
        return $this->getFactory()->createQueueProxy()->receiveMessage($queueOptionTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return bool
     */
    public function acknowledge(QueueMessageTransfer $queueMessageTransfer)
    {
        return $this->getFactory()->createQueueProxy()->acknowledge($queueMessageTransfer);
    }

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return QueueMessageTransfer
     */
    public function handleErrorMessage(QueueMessageTransfer $queueMessageTransfer)
    {
        return $this->getFactory()->createQueueProxy()->handleErrorMessage($queueMessageTransfer);
    }

    /**
     * @api
     *
     * @param string $queueName
     *
     * @return bool
     */
    public function purgeQueue($queueName)
    {
        return $this->getFactory()->createQueueProxy()->purgeQueue($queueName);
    }

    /**
     * @param string $queueName
     *
     * @return bool
     */
    public function deleteQueue($queueName)
    {
        return $this->getFactory()->createQueueProxy()->deleteQueue($queueName);
    }
}
