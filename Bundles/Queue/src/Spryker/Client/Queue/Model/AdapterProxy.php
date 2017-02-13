<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue\Model;

use Generated\Shared\Transfer\QueueConsumerTransfer;
use Generated\Shared\Transfer\QueueMessageTransfer;
use Spryker\Client\Queue\Dependency\Adapter\QueueAdapterInterface;
use Spryker\Client\Queue\Dependency\Exception\QueueAdapterConnectionException;
use Spryker\Client\Queue\Exception\QueueClientConnectionException;

class AdapterProxy implements AdapterProxyInterface
{

    /**
     * @var QueueAdapterInterface
     */
    protected $queueAdapter;

    /**
     * @param QueueAdapterInterface $queueAdapter
     */
    public function __construct(QueueAdapterInterface $queueAdapter)
    {
        $this->queueAdapter = $queueAdapter;
    }

    /**
     * @throws QueueClientConnectionException
     *
     * @return bool
     */
    public function connect()
    {
        try {
            $result = $this->queueAdapter->connect();
        } catch (QueueAdapterConnectionException $exception) {
            throw new QueueClientConnectionException($exception->getMessage(), 0, $exception);
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function disconnect()
    {
        return $this->queueAdapter->disconnect();
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->queueAdapter->isConnected();
    }

    /**
     * @param QueueConsumerTransfer $queueConsumerTransfer
     *
     * @return QueueMessageTransfer
     */
    public function consume(QueueConsumerTransfer $queueConsumerTransfer)
    {
        $this->connectIfNotConnected();

        return $this->queueAdapter->consume($queueConsumerTransfer);
    }

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function confirm(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->connectIfNotConnected();

        $this->queueAdapter->confirm($queueMessageTransfer);
    }

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function publish(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->connectIfNotConnected();

        $this->queueAdapter->publish($queueMessageTransfer);
    }

    /**
     * @return void
     */
    protected function connectIfNotConnected()
    {
        if (!$this->isConnected()) {
            $this->connect();
        }
    }
}
