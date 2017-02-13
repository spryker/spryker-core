<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Dependency\Consumer;

use Generated\Shared\Transfer\QueueMessageTransfer;

interface ConsumerInterface
{

    /**
     * @return QueueMessageTransfer
     */
    public function consume();

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return mixed
     */
    public function confirm(QueueMessageTransfer $queueMessageTransfer);
}
