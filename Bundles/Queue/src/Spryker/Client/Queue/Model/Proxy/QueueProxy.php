<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue\Model\Proxy;

use Generated\Shared\Transfer\QueueMessageTransfer;
use Generated\Shared\Transfer\QueueOptionTransfer;
use Spryker\Client\Queue\Exception\QueueAdapterMissingException;
use Spryker\Client\Queue\Model\Adapter\AdapterInterface;

class QueueProxy implements QueueProxyInterface
{

    /**
     * @var \Spryker\Client\Queue\Model\Adapter\AdapterInterface
     */
    protected $queueAdapter;

    /**
     * @param \Spryker\Client\Queue\Model\Adapter\AdapterInterface $queueAdapter
     */
    public function __construct(AdapterInterface $queueAdapter)
    {
        $this->queueAdapter = $queueAdapter;
    }

    /**
     * @throws \Spryker\Client\Queue\Exception\QueueAdapterMissingException
     *
     * @return mixed
     */
    public function open()
    {
        if (!$this->queueAdapter) {
            throw new QueueAdapterMissingException(sprintf('Queue adapter was not found'));
        }

        return $this->queueAdapter->open();
    }

    /**
     * @param \Generated\Shared\Transfer\QueueOptionTransfer $queueOptionTransfer
     *
     * @return void
     */
    public function createQueue(QueueOptionTransfer $queueOptionTransfer)
    {
        $this->queueAdapter->createQueue($queueOptionTransfer);
    }

    /**
     * @param string $queueName
     * @param callable|null $callback
     * @param \Generated\Shared\Transfer\QueueOptionTransfer $queueOptionTransfer
     *
     * @return mixed
     */
    public function consume($queueName, callable $callback, QueueOptionTransfer $queueOptionTransfer)
    {
        $this->queueAdapter->consume($queueName, $callback, $queueOptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return \Generated\Shared\Transfer\QueueMessageTransfer
     */
    public function encodeMessage(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->queueAdapter->encodeMessage($queueMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return \Generated\Shared\Transfer\QueueMessageTransfer
     */
    public function decodeMessage(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->queueAdapter->decodeMessage($queueMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function acknowledge(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->queueAdapter->acknowledge($queueMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function publish(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->queueAdapter->publish($queueMessageTransfer);
    }

    /**
     * @return bool
     */
    public function close()
    {
        return $this->queueAdapter->close();
    }

}
