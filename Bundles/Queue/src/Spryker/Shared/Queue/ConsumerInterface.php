<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Queue;

use Generated\Shared\Transfer\QueueMessageTransfer;
use Generated\Shared\Transfer\QueueOptionTransfer;

interface ConsumerInterface
{

    /**
     * @param string $queueName
     * @param callable|null $callback
     * @param QueueOptionTransfer $queueOptionTransfer
     *
     * @return mixed
     */
    public function consume($queueName, callable $callback = null, QueueOptionTransfer $queueOptionTransfer);

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function acknowledge(QueueMessageTransfer $queueMessageTransfer);

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return QueueMessageTransfer
     */
    public function decodeMessage(QueueMessageTransfer $queueMessageTransfer);
}
