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
     * @return bool
     */
    public function open()
    {
        return $this->getFactory()->createQueueProxy()->open();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueOptionTransfer $queueOptionTransfer
     *
     * @return void
     */
    public function createQueue(QueueOptionTransfer $queueOptionTransfer)
    {
        $this->getFactory()->createQueueProxy()->createQueue($queueOptionTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return \Generated\Shared\Transfer\QueueMessageTransfer
     */
    public function encodeMessage(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->encodeMessage($queueMessageTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return \Generated\Shared\Transfer\QueueMessageTransfer
     */
    public function decodeMessage(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->decodeMessage($queueMessageTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function acknowledge(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->acknowledge($queueMessageTransfer);
    }

    /**
     * @api
     *
     * @param string $queueName
     * @param callable|null $callback
     * @param \Generated\Shared\Transfer\QueueOptionTransfer $queueOptionTransfer
     *
     * @return mixed
     */
    public function consume($queueName, callable $callback, QueueOptionTransfer $queueOptionTransfer)
    {
        $this->getFactory()->createQueueProxy()->consume($queueName, $callback, $queueOptionTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function publish(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->getFactory()->createQueueProxy()->publish($queueMessageTransfer);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function close()
    {
        return $this->getFactory()->createQueueProxy()->close();
    }

}
