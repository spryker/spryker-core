<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Queue;

use Generated\Shared\Transfer\QueueMessageTransfer;

interface PublisherInterface
{

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return void
     */
    public function publish(QueueMessageTransfer $queueMessageTransfer);

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return QueueMessageTransfer
     */
    public function encodeMessage(QueueMessageTransfer $queueMessageTransfer);

}
