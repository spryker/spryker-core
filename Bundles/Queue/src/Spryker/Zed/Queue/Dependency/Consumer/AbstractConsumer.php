<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Dependency\Consumer;

use Generated\Shared\Transfer\QueueConsumerTransfer;
use Generated\Shared\Transfer\QueueMessageTransfer;
use Spryker\Client\Queue\QueueClientConsumerInterface;

abstract class AbstractConsumer implements ConsumerInterface
{

    /**
     * @var QueueClientConsumerInterface
     */
    protected $queueClient;

    /**
     * @param QueueClientConsumerInterface $queueClient
     */
    public function __construct(QueueClientConsumerInterface $queueClient)
    {
        $this->queueClient = $queueClient;
    }

    /**
     * @return QueueMessageTransfer
     */
    public function consume()
    {
        $queueMessageTransfer = $this->getQueueConsumerTransfer();

        $this->queueClient->consume($queueMessageTransfer);
    }

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function confirm(QueueMessageTransfer $queueMessageTransfer)
    {
        $this->queueClient->confirm($queueMessageTransfer);
    }

    /**
     * @return QueueConsumerTransfer
     */
    abstract protected function getQueueConsumerTransfer();

}
