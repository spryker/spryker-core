<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue\Dependency\Adapter;

use Generated\Shared\Transfer\QueueConsumerTransfer;
use Generated\Shared\Transfer\QueueMessageTransfer;

interface QueueAdapterInterface
{

    /**
     * @return bool
     */
    public function connect();

    /**
     * @return bool
     */
    public function disconnect();

    /**
     * @return bool
     */
    public function isConnected();


    /**
     * @param QueueConsumerTransfer $queueConsumerTransfer
     *
     * @return QueueMessageTransfer
     */
    public function consume(QueueConsumerTransfer $queueConsumerTransfer);

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return mixed
     */
    public function confirm(QueueMessageTransfer $queueMessageTransfer);

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function publish(QueueMessageTransfer $queueMessageTransfer);
}
