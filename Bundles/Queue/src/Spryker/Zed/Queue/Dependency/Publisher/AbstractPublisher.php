<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Dependency\Publisher;

use Generated\Shared\Transfer\QueueMessageTransfer;
use Spryker\Client\Queue\QueueClientPublisherInterface;

abstract class AbstractPublisher implements PublisherInterface
{

    /**
     * @var QueueClientPublisherInterface
     */
    protected $queueClient;

    /**
     * @param QueueClientPublisherInterface $queueClient
     */
    public function __construct(QueueClientPublisherInterface $queueClient)
    {
        $this->queueClient = $queueClient;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function publish($message)
    {
        $queueMessageTransfer = $this->getQueueMessageTransfer($message);

        $this->queueClient->publish($queueMessageTransfer);
    }

    /**
     * @param string $message
     *
     * @return QueueMessageTransfer
     */
    abstract protected function getQueueMessageTransfer($message);
}
