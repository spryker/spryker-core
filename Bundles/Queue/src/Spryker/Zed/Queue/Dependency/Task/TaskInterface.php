<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Dependency\Task;

use Generated\Shared\Transfer\QueueMessageTransfer;

interface TaskInterface
{

    /**
     * @param QueueMessageTransfer $queueMessageTransfer
     *
     * @return bool
     */
    public function processMessage(QueueMessageTransfer $queueMessageTransfer);

}
