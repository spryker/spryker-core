<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue\Model\Internal;

use Generated\Shared\Transfer\QueueMessageTransfer;

interface SenderInterface
{

    /**
     * @param \Generated\Shared\Transfer\QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function sendMessage(QueueMessageTransfer $queueMessageTransfer);

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\QueueMessageTransfer[] $queueMessageTransfers
     *
     * @return void
     */
    public function sendMessages($queueName ,array $queueMessageTransfers);

}
