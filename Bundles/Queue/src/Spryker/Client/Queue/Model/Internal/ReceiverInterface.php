<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue\Model\Internal;

use Generated\Shared\Transfer\QueueMessageTransfer;
use Generated\Shared\Transfer\QueueOptionTransfer;

interface ReceiverInterface
{

    /**
     * @param QueueOptionTransfer $queueOptionTransfer
     *
     * @return QueueMessageTransfer[]
     */
    public function receiveMessages(QueueOptionTransfer $queueOptionTransfer);

    /**
     * @param QueueOptionTransfer $queueOptionTransfer
     *
     * @return QueueMessageTransfer
     */
    public function receiveMessage(QueueOptionTransfer $queueOptionTransfer);

    /**
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return bool
     */
    public function acknowledge(QueueMessageTransfer $queueMessageTransfer);

}
