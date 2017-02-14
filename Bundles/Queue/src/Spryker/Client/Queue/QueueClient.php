<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Generated\Shared\Transfer\QueueOptionTransfer;
use Generated\Shared\Transfer\QueueMessageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method QueueFactory getFactory()
 */
class QueueClient extends AbstractClient implements QueueClientInterface
{

    /**
     * @return bool
     */
    public function open()
    {
        return $this->getFactory()->createQueueProxy()->open();
    }

    /**
     * @param QueueOptionTransfer $queueOptionTransfer
     *
     * @return void
     */
    public function createQueue(QueueOptionTransfer $queueOptionTransfer)
    {
        $this->getFactory()->createQueueProxy()->createQueue($queueOptionTransfer);
    }

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return QueueMessageTransfer
     */
    public function encodeMessage(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->encodeMessage($queueMessageTransfer);
    }

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return QueueMessageTransfer
     */
    public function decodeMessage(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->decodeMessage($queueMessageTransfer);
    }

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function acknowledge(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->acknowledge($queueMessageTransfer);
    }

    /**
     * @param string $queueName
     * @param callable|null $callback
     * @param QueueOptionTransfer $queueOptionTransfer
     *
     * @return mixed
     */
    public function consume($queueName, callable $callback = null, QueueOptionTransfer $queueOptionTransfer)
    {
        $this->getFactory()->createQueueProxy()->consume($queueName, $callback, $queueOptionTransfer);
    }

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function publish(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->publish($queueMessageTransfer);
    }

    /**
     * @return bool
     */
    public function close()
    {
        return $this->getFactory()->createQueueProxy()->close();
    }

}
