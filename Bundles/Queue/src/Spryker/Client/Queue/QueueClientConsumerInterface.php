<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

use Generated\Shared\Transfer\QueueConsumerTransfer;
use Generated\Shared\Transfer\QueueMessageTransfer;

interface QueueClientConsumerInterface
{

    /**
     * @param QueueConsumerTransfer $queueConsumerTransfer
     *
     * @return QueueMessageTransfer
     */
    public function consume(QueueConsumerTransfer $queueConsumerTransfer);

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function confirm(QueueMessageTransfer $queueMessageTransfer);
}
