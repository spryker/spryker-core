<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Generated\Shared\Transfer\QueueConsumerTransfer;
use Generated\Shared\Transfer\QueueMessageTransfer;

/**
 * @method QueueFactory getFactory()
 */
class QueueClient implements QueueClientInterface
{

    /**
     * @return bool
     */
    public function connect()
    {
        return $this
            ->getFactory()
            ->createAdapterProxy()
            ->connect();
    }

    /**
     * @return bool
     */
    public function disconnect()
    {
        return $this
            ->getFactory()
            ->createAdapterProxy()
            ->disconnect();
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this
            ->getFactory()
            ->createAdapterProxy()
            ->isConnected();
    }

    /**
     * @param QueueConsumerTransfer $queueConsumerTransfer
     *
     * @return QueueMessageTransfer
     */
    public function consume(QueueConsumerTransfer $queueConsumerTransfer)
    {
        return $this
            ->getFactory()
            ->createAdapterProxy()
            ->consume($queueConsumerTransfer);
    }

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function confirm(QueueMessageTransfer $queueMessageTransfer)
    {
        $this
            ->getFactory()
            ->createAdapterProxy()
            ->confirm($queueMessageTransfer);
    }

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function publish(QueueMessageTransfer $queueMessageTransfer)
    {
        $this
            ->getFactory()
            ->createAdapterProxy()
            ->publish($queueMessageTransfer);
    }
}
